<?php

namespace src\Repositories;

use PDO;
use src\Exceptions\ServerException;
use src\Models\Property;

final readonly class PropertyRepository
{
    public function __construct(
        private PDO $db
    ) {}

    public function findById(int $id): Property
    {

        $stmt = $this->db->prepare('SELECT * FROM properties WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new ServerException(ServerException::PROPERTY_FIND_FAILED);
        }
        return $this->hydrate($data);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM properties');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrate'], $data);
    }

    public function hydrate(array $data): Property
    {
        return new Property(
            id: $data['id'],
            country: $data['country'],
            postalCode: $data['postal_code'],
            city: $data['city'],
            address: $data['address']
        );
    }

    public function create(string $country, string $postalCode, string $city, string $address): Property
    {
        $stmt = $this->db->prepare('INSERT INTO properties (country, postal_code, city, address) VALUES (:country, :postal_code, :city, :address)');
        $stmt->execute([
            ':country' => $country,
            ':postal_code' => $postalCode,
            ':city' => $city,
            ':address' => $address
        ]);
        if ($stmt->rowCount() === 0) {
            throw new ServerException(ServerException::PROPERTY_CREATE_FAILED);
        }
        $data['id'] = $this->db->lastInsertId();
        return $this->hydrate($data);
    }

    public function update(Property $property): void
    {
        $stmt = $this->db->prepare('UPDATE properties SET country = :country, postal_code = :postal_code, city = :city, address = :address WHERE id = :id');
        $stmt->execute([
            ':id' => $property->id,
            ':country' => $property->country,
            ':postal_code' => $property->postalCode,
            ':city' => $property->city,
            ':address' => $property->address
        ]);
        if ($stmt->rowCount() === 0) {
            throw new ServerException(ServerException::PROPERTY_UPDATE_FAILED);
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM properties WHERE id = :id');
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() === 0) {
            throw new ServerException(ServerException::PROPERTY_DELETE_FAILED);
        }
    }

    public function assignToOrganization(int $propertyId, int $organizationId): void
    {
        $stmt = $this->db->prepare('INSERT INTO organization_property_relations (property_id, organization_id) VALUES (:property_id, :organization_id)');
        $stmt->execute([
            ':property_id' => $propertyId,
            ':organization_id' => $organizationId
        ]);
        if ($stmt->rowCount() === 0) {
            throw new ServerException(ServerException::PROPERTY_ASSIGN_FAILED);
        }
    }

    public function findByOrganizationId(int $organizationId): array
    {
        $sql = <<<'SQL'
        SELECT p.* FROM properties p
            JOIN organization_property_relations opr 
                ON p.id = opr.property_id
        WHERE opr.organization_id = :organization_id
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':organization_id' => $organizationId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrate'], $data);
    }

    public function isAssignedToOrganization(int $propertyId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM organization_property_relations WHERE property_id = :property_id');
        $stmt->execute([':property_id' => $propertyId]);
        return (bool)$stmt->fetchColumn();
    }

    public function unassignFromOrganization(int $propertyId): void
    {
        $stmt = $this->db->prepare('DELETE FROM organization_property_relations WHERE property_id = :property_id');
        $stmt->execute([
            ':property_id' => $propertyId
        ]);
    }
}