<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Application\Port\RentalUnitRepository as RentalUnitRepositoryInterface;
use DateTimeImmutable;
use Domain\Entity\RentalUnit;
use Domain\ValueObject\PropertyId;
use Domain\ValueObject\RentalUnitId;
use PDO;
use PDOException;
use Throwable;

/**
 *
 */
final readonly class RentalUnitRepository implements RentalUnitRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(
        private PDO $db
    ) {
    }


    /**
     * @param RentalUnitId $id
     *
     * @return RentalUnit
     * @throws DatabaseException
     */
    public function findById(RentalUnitId $id): RentalUnit
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM rental_units WHERE id = :id');
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
     * @return RentalUnit[]
     * @throws DatabaseException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT * FROM rental_units');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param RentalUnit $rentalUnit
     *
     * @return void
     * @throws DatabaseException
     */
    public function save(RentalUnit $rentalUnit): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO rental_units (
                id, property_id, name, created_at, updated_at
            ) VALUES (
                :id, :property_id, :name, :created_at, :updated_at
            )
            ON DUPLICATE KEY UPDATE 
                property_id = VALUES(property_id),
                name = VALUES(name),
                updated_at = VALUES(updated_at)
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $rentalUnit->id->toString());
            $stmt->bindValue(':property_id', $rentalUnit->propertyId->toString());
            $stmt->bindValue(':name', $rentalUnit->name);
            $stmt->bindValue(':created_at', $rentalUnit->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $rentalUnit->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param RentalUnitId $id
     *
     * @return void
     * @throws DatabaseException
     */
    public function delete(RentalUnitId $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM rental_units WHERE id = :id');
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
     *     property_id: string,
     *     name: string,
     *     created_at: string,
     *     updated_at: string,
     * } $data
     *
     * @return RentalUnit
     * @throws DatabaseException
     */
    private function hydrate(array $data): RentalUnit
    {
        try {
            return new RentalUnit(
                id: new RentalUnitId($data['id']),
                propertyId: new PropertyId($data['property_id']),
                name: $data['name'],
                createdAt: new DateTimeImmutable($data['created_at']),
                updatedAt: new DateTimeImmutable($data['updated_at']),
            );
        } catch (Throwable) {
            throw new DatabaseException(DatabaseException::RECORD_NOT_FOUND);
        }
    }
}
