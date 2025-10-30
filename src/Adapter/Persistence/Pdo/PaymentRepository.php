<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Application\Port\PaymentRepository as PaymentRepositoryInterface;
use DateTimeImmutable;
use Domain\Entity\Payment;
use Domain\Types\Currency;
use Domain\ValueObject\Money;
use Domain\ValueObject\PaymentId;
use PDO;
use PDOException;
use Throwable;

/**
 *
 */
final readonly class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(
        private PDO $db
    ) {
    }

    /**
     * @throws DatabaseException
     */
    public function findById(PaymentId $id): Payment
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM payments WHERE id = :id');
            $stmt->bindValue(':id', $id->toString());
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                throw new DatabaseException(DatabaseException::RECORD_NOT_FOUND);
            }

            return $this->hydrate($data);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT * FROM payments');
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $payments);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    public function save(Payment $payment): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO payments(
                id, amount, currency, created_at, due_at, paid_at
            )
            VALUES(
                :id, :amount, :currency, :created_at, :due_at, :paid_at
            )
            ON DUPLICATE KEY UPDATE 
                amount = :amount,
                currency = :currency,
                created_at = :created_at,
                due_at = :due_at,
                paid_at = :paid_at
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $payment->id->toString());
            $stmt->bindValue(':amount', $payment->amount->minorAmount, PDO::PARAM_INT);
            $stmt->bindValue(':currency', $payment->amount->currency->value);
            $stmt->bindValue(':created_at', $payment->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':due_at', $payment->dueAt?->format('Y-m-d H:i:s'));
            $stmt->bindValue(':paid_at', $payment->paidAt?->format('Y-m-d H:i:s'));

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new DatabaseException(DatabaseException::QUERY_FAILED);
            }
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    public function delete(PaymentId $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM payments WHERE id = :id');
            $stmt->bindValue(':id', $id->toString());
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new DatabaseException(DatabaseException::RECORD_NOT_FOUND);
            }
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    private function hydrate(array $data): Payment
    {
        try {
            return new Payment(
                id:         new PaymentId($data['id']),
                amount:     new Money($data['amount'], Currency::from($data['currency'])),
                createdAt:  new DateTimeImmutable($data['created_at']),
                dueAt:      new DateTimeImmutable($data['due_at']),
                paidAt:     $data['paid_at'] == null ? null : new DateTimeImmutable($data['paid_at'])
            );
        } catch (Throwable) {
            throw new DatabaseException(DatabaseException::HYDRATION_FAILED);
        }
    }
}
