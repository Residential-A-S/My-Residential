<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use DateTimeImmutable;
use Domain\Exception\RentalAgreementException;
use Domain\Factory\RentalAgreementFactory;
use PDO;
use PDOException;
use Shared\Exception\BaseException;
use Shared\Exception\ServerException;
use src\Entity\RentalAgreement;
use Throwable;

final readonly class RentalAgreementRepository
{
    public function __construct(
        private PDO $db,
        private RentalAgreementFactory $factory
    ) {
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     */
    public function findById(int $id): RentalAgreement
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM rental_agreements WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_NOT_FOUND);
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
            $stmt = $this->db->query('SELECT * FROM rental_agreements');
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
    public function create(RentalAgreement $rentalAgreement): RentalAgreement
    {
        try {
            $sql = <<<'SQL'
                INSERT INTO rental_agreements
                    (rental_unit_id, start_date, end_date, status, payment_interval, created_at, updated_at)
                VALUES 
                    (:rental_unit_id, :start_date, :end_date, :status, :payment_interval, :created_at, :updated_at)
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':rental_unit_id', $rentalAgreement->rentalUnitId, PDO::PARAM_INT);
            $stmt->bindValue(':start_date', $rentalAgreement->startDate->format('Y-m-d H:i:s'));
            $stmt->bindValue(':end_date', $rentalAgreement->endDate->format('Y-m-d H:i:s'));
            $stmt->bindValue(':status', $rentalAgreement->status);
            $stmt->bindValue(':payment_interval', $rentalAgreement->paymentInterval);
            $stmt->bindValue(':created_at', $rentalAgreement->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $rentalAgreement->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_CREATE_FAILED);
            }
            return $this->factory->withId($rentalAgreement, (int)$this->db->lastInsertId());
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    public function update(RentalAgreement $rentalAgreement): void
    {
        try {
            $sql = <<<'SQL'
                UPDATE rental_agreements
                SET rental_unit_id = :rental_unit_id,
                    start_date = :start_date,
                    end_date = :end_date,
                    status = :status,
                    payment_interval = :payment_interval,
                    updated_at = :updated_at
                WHERE id = :id
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $rentalAgreement->id, PDO::PARAM_INT);
            $stmt->bindValue(':rental_unit_id', $rentalAgreement->rentalUnitId, PDO::PARAM_INT);
            $stmt->bindValue(':start_date', $rentalAgreement->startDate->format('Y-m-d H:i:s'));
            $stmt->bindValue(':end_date', $rentalAgreement->endDate->format('Y-m-d H:i:s'));
            $stmt->bindValue(':status', $rentalAgreement->status);
            $stmt->bindValue(':payment_interval', $rentalAgreement->paymentInterval);
            $stmt->bindValue(':updated_at', $rentalAgreement->updatedAt->format('Y-m-d H:i:s'));

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
            $stmt = $this->db->prepare('DELETE FROM rental_agreements WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_NOT_FOUND);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws RentalAgreementException
     */
    public function requireId(int $id): RentalAgreement
    {
        try {
            return $this->findById($id);
        } catch (BaseException) {
            throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_NOT_FOUND);
        }
    }

    /**
     * @throws ServerException
     */
    private function hydrate(array $data): RentalAgreement
    {
        try {
            return $this->factory->withId(
                new RentalAgreement(
                    (int)$data['id'],
                    (int)$data['rental_unit_id'],
                    new DateTimeImmutable($data['start_date']),
                    new DateTimeImmutable($data['end_date']),
                    $data['status'],
                    $data['payment_interval'],
                    new DateTimeImmutable($data['created_at']),
                    new DateTimeImmutable($data['updated_at'])
                ),
                (int)$data['id']
            );
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
