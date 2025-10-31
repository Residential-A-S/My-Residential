<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Application\Port\RentChargeRepository as RentChargeRepositoryInterface;
use DateTimeImmutable;
use Domain\Entity\RentCharge;
use Domain\ValueObject\PaymentId;
use Domain\ValueObject\RentalAgreementId;
use Domain\ValueObject\RentChargeId;
use PDO;
use PDOException;
use Throwable;

/**
 *
 */
final readonly class RentChargeRepository implements RentChargeRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(
        private PDO $db
    ) {
    }

    /**
     * @param RentChargeId $id
     *
     * @return RentCharge
     * @throws DatabaseException
     */
    public function findById(RentChargeId $id): RentCharge
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM rental_agreements_payments WHERE id = :id');
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
     * @param PaymentId $paymentId
     *
     * @return RentCharge
     * @throws DatabaseException
     */
    public function findByPaymentId(PaymentId $paymentId): RentCharge
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM rental_agreements_payments WHERE payment_id = :id');
            $stmt->bindValue(':id', $paymentId->toString());
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
     * @param RentalAgreementId $rentalAgreementId
     *
     * @return RentCharge[]
     * @throws DatabaseException
     */
    public function findByRentalAgreementId(RentalAgreementId $rentalAgreementId): array
    {
        try {
            $sql = 'SELECT * FROM rental_agreements_payments WHERE rental_agreement_id = :rentalAgreementId';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':rentalAgreementId', $rentalAgreementId->toString());
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @return RentCharge[]
     * @throws DatabaseException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT * FROM rental_agreements_payments');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param RentCharge $rentCharge
     *
     * @return void
     * @throws DatabaseException
     */
    public function save(RentCharge $rentCharge): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO rental_agreements_payments (
                id, rental_agreement_id, payment_id, period_start, period_end
            ) VALUES (
                :id, :rental_agreement_id, :payment_id, :period_start, :period_end
            )
            ON DUPLICATE KEY UPDATE 
                rental_agreement_id = :rental_agreement_id,
                payment_id = :payment_id,
                period_start = :period_start,
                period_end = :period_end
            SQL;

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $rentCharge->id->toString());
            $stmt->bindValue(':rental_agreement_id', $rentCharge->rentalAgreementId->toString());
            $stmt->bindValue(':payment_id', $rentCharge->paymentId->toString());
            $stmt->bindValue(':period_start', $rentCharge->periodStart->format('Y-m-d'));
            $stmt->bindValue(':period_end', $rentCharge->periodEnd->format('Y-m-d'));

            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param RentChargeId $id
     *
     * @return void
     * @throws DatabaseException
     */
    public function delete(RentChargeId $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM rental_agreements_payments WHERE id = :id');
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
     * @param array{
     *     id: string,
     *     rental_agreement_id: string,
     *     payment_id: string,
     *     period_start: string,
     *     period_end: string,
     * } $data
     *
     * @return RentCharge
     * @throws DatabaseException
     */
    private function hydrate(array $data): RentCharge
    {
        try {
            return new RentCharge(
                id: new RentChargeId($data['id']),
                rentalAgreementId: new RentalAgreementId($data['rental_agreement_id']),
                paymentId: new PaymentId($data['payment_id']),
                periodStart: new DateTimeImmutable($data['period_start']),
                periodEnd: new DateTimeImmutable($data['period_end'])
            );
        } catch (Throwable) {
            throw new DatabaseException(DatabaseException::HYDRATION_FAILED);
        }
    }
}
