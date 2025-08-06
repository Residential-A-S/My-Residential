<?php

declare(strict_types=1);

namespace src\Repositories;

use DateTimeImmutable;
use PDOException;
use src\Exceptions\PaymentException;
use src\Exceptions\ServerException;
use src\Factories\PaymentFactory;
use src\Models\Payment;
use PDO;
use Throwable;

final readonly class PaymentRepository
{
    public function __construct(
        private PDO $db,
        private PaymentFactory $factory,
    ) {
    }

    /**
     * @throws PaymentException
     * @throws ServerException
     */
    public function findById(int $id): Payment
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM payments WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                throw new PaymentException(PaymentException::NOT_FOUND);
            }

            return $this->hydrate($data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT * FROM payments');
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $payments);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws PaymentException
     * @throws ServerException
     */
    public function create(Payment $payment): Payment
    {
        try {
            $sql = <<<'SQL'
                INSERT INTO payments 
                    (amount, currency, created_at, due_at, paid_at)
                VALUES 
                    (:amount, :currency, :created_at, :due_at, paid_at)
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':amount', $payment->amount);
            $stmt->bindValue(':currency', $payment->currency);
            $stmt->bindValue(':created_at', $payment->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':due_at', $payment->dueAt?->format('Y-m-d H:i:s'));
            $stmt->bindValue(':paid_at', $payment->paidAt?->format('Y-m-d H:i:s'));

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new PaymentException(PaymentException::CREATE_FAILED);
            }
            $paymentId = (int)$this->db->lastInsertId();
            return $this->factory->withId($payment, $paymentId);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    public function update(Payment $payment): void
    {
        try {
            $sql = <<<'SQL'
                UPDATE payments
                SET amount = :amount,
                    currency = :currency,
                    due_at = :due_at,
                    paid_at = :paid_at
                WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $payment->id, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $payment->amount);
            $stmt->bindValue(':currency', $payment->currency);
            $stmt->bindValue(':due_at', $payment->dueAt?->format('Y-m-d H:i:s'));
            $stmt->bindValue(':paid_at', $payment->paidAt?->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     * @throws PaymentException
     */
    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM payments WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new PaymentException(PaymentException::NOT_FOUND);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    private function hydrate(array $data): Payment
    {
        try {
            return new Payment(
                id:         $data['id'],
                amount:     (float)$data['amount'],
                currency:   $data['currency'],
                createdAt:  new DateTimeImmutable($data['created_at']),
                dueAt:      new DateTimeImmutable($data['due_at']),
                paidAt:     new DateTimeImmutable($data['paid_at'])
            );
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
