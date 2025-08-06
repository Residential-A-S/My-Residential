<?php

declare(strict_types=1);

namespace src\Repositories;

use DateTimeImmutable;
use PDOException;
use src\Exceptions\RentalUnitException;
use src\Exceptions\ServerException;
use src\Factories\RentalUnitFactory;
use src\Models\RentalUnit;
use PDO;
use Throwable;

final readonly class RentalUnitRepository
{
    public function __construct(
        private PDO $db,
        private RentalUnitFactory $factory,
    ) {
    }

    /**
     * @throws RentalUnitException
     * @throws ServerException
     */
    public function findById(int $id): RentalUnit
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM rental_units WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new RentalUnitException(RentalUnitException::NOT_FOUND);
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
            $stmt = $this->db->query('SELECT * FROM rental_units');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws RentalUnitException
     * @throws ServerException
     */
    public function create(RentalUnit $rentalUnit): RentalUnit
    {
        try {
            $sql = <<<'SQL'
                INSERT INTO rental_units 
                    (property_id, name, status, created_at, updated_at)
                VALUES
                    (:property_id, :name, :status, :created_at, :updated_at)
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':property_id', $rentalUnit->propertyId, PDO::PARAM_INT);
            $stmt->bindValue(':name', $rentalUnit->name);
            $stmt->bindValue(':status', $rentalUnit->status);
            $stmt->bindValue(':created_at', $rentalUnit->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $rentalUnit->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new RentalUnitException(RentalUnitException::CREATE_FAILED);
            }
            return $this->factory->withId($rentalUnit, (int)$this->db->lastInsertId());
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    public function update(RentalUnit $rentalUnit): void
    {
        try {
            $sql = <<<'SQL'
                UPDATE rental_units
                SET property_id = :property_id, name = :name, status = :status, updated_at = :updated_at
                WHERE id = :id
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $rentalUnit->id, PDO::PARAM_INT);
            $stmt->bindValue(':property_id', $rentalUnit->propertyId, PDO::PARAM_INT);
            $stmt->bindValue(':name', $rentalUnit->name);
            $stmt->bindValue(':status', $rentalUnit->status);
            $stmt->bindValue(':updated_at', $rentalUnit->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     * @throws RentalUnitException
     */
    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM rental_units WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new RentalUnitException(RentalUnitException::NOT_FOUND);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    private function hydrate(array $data): RentalUnit
    {
        try {
            return new RentalUnit(
                id: (int)$data['id'],
                propertyId: (int)$data['property_id'],
                name: $data['name'],
                status: $data['status'],
                createdAt: new DateTimeImmutable($data['created_at']),
                updatedAt: new DateTimeImmutable($data['updated_at']),
            );
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
