<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Application\Port\RentalUnitRepository as RentalUnitRepositoryInterface;
use DateTimeImmutable;
use Domain\Types\PaymentInterval;
use Domain\Types\RentalAgreementStatus;
use Domain\ValueObject\RentalAgreementId;
use Domain\ValueObject\RentalUnitId;
use PDO;
use PDOException;
use Domain\Entity\RentalAgreement;
use Throwable;

/**
 *
 */
final readonly class RentalAgreementRepository implements RentalUnitRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(
        private PDO $db
    ) {
    }

    /**
     * @param RentalAgreementId $id
     *
     * @return RentalAgreement
     * @throws DatabaseException
     */
    public function findById(RentalAgreementId $id): RentalAgreement
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM rental_agreements WHERE id = :id');
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
     * @return RentalAgreement[]
     * @throws DatabaseException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT * FROM rental_agreements');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param RentalAgreement $rentalAgreement
     *
     * @return void
     * @throws DatabaseException
     */
    public function save(RentalAgreement $rentalAgreement): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO rental_agreements(
                id, rental_unit_id, start_date, end_date, status, payment_interval, created_at, updated_at
            ) VALUES (
                :id, :rental_unit_id, :start_date, :end_date, :status, :payment_interval, :created_at, :updated_at
            )
            ON DUPLICATE KEY UPDATE 
                rental_unit_id = :rental_unit_id,
                start_date = :start_date,
                end_date = :end_date,
                status = :status,
                payment_interval = :payment_interval,
                updated_at = :updated_at
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $rentalAgreement->id->toString());
            $stmt->bindValue(':rental_unit_id', $rentalAgreement->rentalUnitId->toString());
            $stmt->bindValue(':start_date', $rentalAgreement->startDate->format('Y-m-d H:i:s'));
            $stmt->bindValue(':end_date', $rentalAgreement->endDate->format('Y-m-d H:i:s'));
            $stmt->bindValue(':status', $rentalAgreement->status->value);
            $stmt->bindValue(':payment_interval', $rentalAgreement->paymentInterval->value);
            $stmt->bindValue(':created_at', $rentalAgreement->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $rentalAgreement->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param RentalAgreementId $id
     *
     * @return void
     * @throws DatabaseException
     */
    public function delete(RentalAgreementId $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM rental_agreements WHERE id = :id');
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
     *     rental_unit_id: string,
     *     start_date: string,
     *     end_date: string,
     *     status: string,
     *     payment_interval: string,
     *     created_at: string,
     *     updated_at: string
     * } $data
     *
     * @return RentalAgreement
     * @throws DatabaseException
     */
    private function hydrate(array $data): RentalAgreement
    {
        try {
            return new RentalAgreement(
                new RentalAgreementId($data['id']),
                new RentalUnitId($data['rental_unit_id']),
                new DateTimeImmutable($data['start_date']),
                new DateTimeImmutable($data['end_date']),
                RentalAgreementStatus::from($data['status']),
                PaymentInterval::from($data['payment_interval']),
                new DateTimeImmutable($data['created_at']),
                new DateTimeImmutable($data['updated_at'])
            );
        } catch (Throwable) {
            throw new DatabaseException(DatabaseException::HYDRATION_FAILED);
        }
    }
}
