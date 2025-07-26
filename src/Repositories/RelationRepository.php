<?php

namespace src\Repositories;

use PDO;
use src\Exceptions\ServerException;

final readonly class RelationRepository
{
    public function __construct(
        private PDO $db,
    ) {}

    public function linkUserOrganization(int $userId, int $organizationId): void
    {
        $stmt = $this->db->prepare('INSERT INTO user_organization_relations (organization_id, user_id) VALUES (:organization_id, :user_id)');
        $stmt->execute([
            ':organization_id' => $organizationId,
            ':user_id' => $userId
        ]);
        if( $stmt->rowCount() === 0) {
            throw new ServerException(ServerException::ORGANIZATION_USER_LINK_FAILED);
        }
    }

    public function unlinkUserOrganization(int $userId, int $organizationId): void
    {
        $stmt = $this->db->prepare('DELETE FROM user_organization_relations WHERE organization_id = :organization_id AND user_id = :user_id');
        $stmt->execute([
            ':organization_id' => $organizationId,
            ':user_id' => $userId
        ]);
        if( $stmt->rowCount() === 0) {
            throw new ServerException(ServerException::ORGANIZATION_USER_UNLINK_FAILED);
        }
    }

    public function linkPropertyOrganization(int $propertyId, int $organizationId): void
    {
        $stmt = $this->db->prepare('INSERT INTO organization_property_relations (organization_id, property_id) VALUES (:organization_id, :property_id)');
        $stmt->execute([
            ':organization_id' => $organizationId,
            ':property_id' => $propertyId
        ]);
        if( $stmt->rowCount() === 0) {
            throw new ServerException(ServerException::ORGANIZATION_PROPERTY_LINK_FAILED);
        }
    }

    public function unlinkPropertyOrganization(int $propertyId, int $organizationId): void
    {
        $stmt = $this->db->prepare('DELETE FROM organization_property_relations WHERE organization_id = :organization_id AND property_id = :property_id');
        $stmt->execute([
            ':organization_id' => $organizationId,
            ':property_id' => $propertyId
        ]);
        if( $stmt->rowCount() === 0) {
            throw new ServerException(ServerException::ORGANIZATION_PROPERTY_UNLINK_FAILED);
        }
    }
}