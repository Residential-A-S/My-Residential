<?php

declare(strict_types=1);

namespace src\Repositories;

use DateTimeImmutable;
use PDOException;
use src\Exceptions\PropertyException;
use src\Exceptions\ServerException;
use src\Factories\PropertyFactory;
use src\Models\Property;
use PDO;
use Throwable;

final readonly class PropertyRepository
{
    public function __construct(
        private PDO $db,
        private PropertyFactory $factory
    ) {
    }

    /**
     * @throws ServerException
     * @throws PropertyException
     */
    public function findById(int $id): Property
    {
        try {
            $sql = 'SELECT * FROM properties WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                throw new PropertyException(PropertyException::NOT_FOUND);
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
            $sql = 'SELECT * FROM properties';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     * @throws PropertyException
     */
    public function create(Property $property): Property
    {
        try {
            $sql = <<<'SQL'
                INSERT INTO properties (
                                        organization_id, 
                                        street_name, 
                                        street_number, 
                                        zip_code, 
                                        city, 
                                        country, 
                                        created_at, 
                                        updated_at
                                        )
                VALUES (
                        :organization_id, 
                        :street_name, 
                        :street_number, 
                        :zip_code, 
                        :city, 
                        :country, 
                        :created_at, 
                        :updated_at
                        )
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':organization_id', $property->organizationId, PDO::PARAM_INT);
            $stmt->bindValue(':street_name', $property->streetName);
            $stmt->bindValue(':street_number', $property->streetNumber);
            $stmt->bindValue(':zip_code', $property->zipCode);
            $stmt->bindValue(':city', $property->city);
            $stmt->bindValue(':country', $property->country);
            $stmt->bindValue(':created_at', $property->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $property->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new PropertyException(PropertyException::CREATE_FAILED);
            }
            $id = (int)$this->db->lastInsertId();
            return $this->factory->withId($property, $id);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    public function update(Property $property): void
    {
        try {
            $sql = <<<'SQL'
                UPDATE properties
                SET organization_id = :organization_id,
                    street_name = :street_name,
                    street_number = :street_number,
                    zip_code = :zip_code,
                    city = :city,
                    country = :country,
                    updated_at = :updated_at
                WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $property->id, PDO::PARAM_INT);
            $stmt->bindValue(':organization_id', $property->organizationId, PDO::PARAM_INT);
            $stmt->bindValue(':street_name', $property->streetName);
            $stmt->bindValue(':street_number', $property->streetNumber);
            $stmt->bindValue(':zip_code', $property->zipCode);
            $stmt->bindValue(':city', $property->city);
            $stmt->bindValue(':country', $property->country);
            $stmt->bindValue(':updated_at', $property->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     * @throws PropertyException
     */
    public function delete(int $id): void
    {
        try {
            $sql = 'DELETE FROM properties WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new PropertyException(PropertyException::NOT_FOUND);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
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
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
