<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Adapter\Persistence\PropertyException;
use Application\Port\PropertyRepository;
use DateTimeImmutable;
use Domain\Entity\Property;
use Domain\ValueObject\PropertyId;
use PDO;
use PDOException;
use Throwable;

/**
 *
 */
final readonly class PropertyRepository implements PropertyRepository
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
    public function findById(PropertyId $id): Property
    {
        try {
            $sql = 'SELECT * FROM properties WHERE id = :id';
            $stmt = $this->db->prepare($sql);
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
            $sql = 'SELECT * FROM properties';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    public function save(Property $property): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO properties (
                id, organization_id, street_name, street_number, zip_code, city, country, created_at, updated_at
            ) VALUES (
                :id, :organization_id, :street_name, :street_number, :zip_code, :city, :country, :created_at, :updated_at
            )
            ON DUPLICATE KEY UPDATE 
                organization_id = :organization_id,
                street_name = :street_name,
                street_number = :street_number,
                zip_code = :zip_code,
                city = :city,
                country = :country,
                updated_at = :updated_at
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $property->id->toString());
            $stmt->bindValue(':organization_id', $property->organizationId->toString());
            $stmt->bindValue(':street_name', $property->address->streetName);
            $stmt->bindValue(':street_number', $property->address->streetNumber);
            $stmt->bindValue(':zip_code', $property->address->zipCode);
            $stmt->bindValue(':city', $property->address->city);
            $stmt->bindValue(':country', $property->address->country);
            $stmt->bindValue(':created_at', $property->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $property->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new DatabaseException(DatabaseException::QUERY_FAILED);
            }
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    public function delete(PropertyId $id): void
    {
        try {
            $sql = 'DELETE FROM properties WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new PropertyException(PropertyException::NOT_FOUND);
            }
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    private function hydrate(array $data): Property
    {
        try {
            return new Property(
                id: (int)$data['id'],
                organizationId: (int)$data['organization_id'],
                streetName: $data['street_name'],
                streetNumber: $data['street_number'],
                zipCode: $data['zip_code'],
                city: $data['city'],
                country: $data['country'],
                createdAt: new DateTimeImmutable($data['created_at']),
                updatedAt: new DateTimeImmutable($data['updated_at'])
            );
        } catch (Throwable) {
            throw new DatabaseException(DatabaseException::HYDRATION_FAILED);
        }
    }
}
