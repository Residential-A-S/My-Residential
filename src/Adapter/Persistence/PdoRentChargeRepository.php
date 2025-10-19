<?php

declare(strict_types=1);

namespace Adapter\Persistence;

use DateTimeImmutable;
use PDOException;
use PDOStatement;
use Domain\Exception\RentalAgreementException;
use Shared\Exception\ServerException;
use src\Entity\RentalAgreementPayment;
use PDO;
use Throwable;

final readonly class PdoRentChargeRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     */
    public function findByPaymentId(int $id): RentalAgreementPayment
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM rental_agreements_payments WHERE payment_id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_PAYMENT_NOT_FOUND);
            }

            return $this->hydrate($data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    public function findByRentalAgreementId(int $rentalAgreementId): array
    {
        try {
            $sql = 'SELECT * FROM rental_agreements_payments WHERE rental_agreement_id = :rentalAgreementId';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':rentalAgreementId', $rentalAgreementId, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
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
            $stmt = $this->db->query('SELECT * FROM rental_agreements_payments');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     */
    public function create(RentalAgreementPayment $rentalAgreementPayment): RentalAgreementPayment
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO rental_agreements_payments (
                                                    rental_agreement_id, 
                                                    payment_id,
                                                    period_start,
                                                    period_end
            ) VALUES (
                      :rental_agreement_id, 
                      :payment_id,
                      :period_start,
                      :period_end
            )
            SQL;

            $stmt = $this->db->prepare($sql);
            $this->bindRentalAgreementPaymentValues($stmt, $rentalAgreementPayment);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_PAYMENT_CREATE_FAILED);
            }

            return $rentalAgreementPayment;
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    public function update(RentalAgreementPayment $rentalAgreementPayment): void
    {
        try {
            $sql = <<<'SQL'
            UPDATE rental_agreements_payments
            SET rental_agreement_id = :rental_agreement_id,
                payment_id = :payment_id,
                period_start = :period_start,
                period_end = :period_end
            WHERE payment_id = :payment_id
            SQL;

            $stmt = $this->db->prepare($sql);
            $this->bindRentalAgreementPaymentValues($stmt, $rentalAgreementPayment);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     */
    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM rental_agreements_payments WHERE payment_id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_PAYMENT_NOT_FOUND);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    private function hydrate(array $data): RentalAgreementPayment
    {
        try {
            return new RentalAgreementPayment(
                rentalAgreementId: (int)$data['rental_agreement_id'],
                paymentId: (int)$data['payment_id'],
                periodStart: new DateTimeImmutable($data['period_start']),
                periodEnd: new DateTimeImmutable($data['period_end'])
            );
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }

    private function bindRentalAgreementPaymentValues(
        PDOStatement $stmt,
        RentalAgreementPayment $rentalAgreementPayment
    ): void {
        $stmt->bindValue(':rental_agreement_id', $rentalAgreementPayment->rentalAgreementId, PDO::PARAM_INT);
        $stmt->bindValue(':payment_id', $rentalAgreementPayment->paymentId, PDO::PARAM_INT);
        $stmt->bindValue(':period_start', $rentalAgreementPayment->periodStart->format('Y-m-d H:i:s'));
        $stmt->bindValue(':period_end', $rentalAgreementPayment->periodEnd->format('Y-m-d H:i:s'));
    }
}
