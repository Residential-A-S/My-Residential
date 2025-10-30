<?php

namespace Adapter\Persistence\Pdo;

use PDO;
use src\Types\Role;

final readonly class UserOrganizationRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function addUserToOrganization(int $userId, int $organizationId, Role $role): void
    {
        $sql = <<<SQL
        INSERT INTO users_organizations (user_id, organization_id, role) 
        VALUES (:user_id, :organization_id, :role)
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':organization_id', $organizationId, PDO::PARAM_INT);
        $stmt->bindValue(':role', $role->value);
        $stmt->execute();
    }

    public function removeUserFromOrganization(int $userId, int $organizationId): void
    {
        $sql = <<<SQL
        DELETE FROM users_organizations 
        WHERE user_id = :user_id AND organization_id = :organization_id
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':organization_id', $organizationId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function userExistsInOrganization(int $userId, int $organizationId): bool
    {
        $sql = <<<SQL
        SELECT COUNT(*) 
        FROM users_organizations 
        WHERE user_id = :user_id AND organization_id = :organization_id
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':organization_id', $organizationId, PDO::PARAM_INT);
        $stmt->execute();

        return (bool)$stmt->fetchColumn();
    }

    public function findUserRoleInOrganization(int $userId, int $organizationId): Role
    {
        $sql = <<<SQL
        SELECT role 
        FROM users_organizations 
        WHERE user_id = :user_id AND organization_id = :organization_id
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':organization_id', $organizationId, PDO::PARAM_INT);
        $stmt->execute();

        return Role::from($stmt->fetchColumn());
    }

    public function changeUserRoleInOrganization(int $userId, int $organizationId, Role $role): void
    {
        $sql = <<<SQL
        UPDATE users_organizations 
        SET role = :role 
        WHERE user_id = :user_id AND organization_id = :organization_id
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':organization_id', $organizationId, PDO::PARAM_INT);
        $stmt->bindValue(':role', $role->value);
        $stmt->execute();
    }
}
