<?php

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Application\Port\UserOrganizationRepository as UserOrganizationRepositoryInterface;
use Domain\ValueObject\OrganizationId;
use Domain\ValueObject\UserId;
use PDO;
use PDOException;

/**
 *
 */
final readonly class UserOrganizationRepository implements UserOrganizationRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(
        private PDO $db
    ) {
    }

    /**
     * @param UserId $userId
     * @param OrganizationId $organizationId
     *
     * @return void
     * @throws DatabaseException
     */
    public function addUserToOrganization(UserId $userId, OrganizationId $organizationId): void
    {
        try{
            $sql = <<<SQL
            INSERT INTO users_organizations (user_id, organization_id, role) 
            VALUES (:user_id, :organization_id, :role)
            SQL;

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId->toString());
            $stmt->bindValue(':organization_id', $organizationId->toString());
            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param UserId $userId
     * @param OrganizationId $organizationId
     *
     * @return void
     * @throws DatabaseException
     */
    public function removeUserFromOrganization(UserId $userId, OrganizationId $organizationId): void
    {
        try{
            $sql = <<<SQL
            DELETE FROM users_organizations 
            WHERE user_id = :user_id AND organization_id = :organization_id
            SQL;

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId->toString());
            $stmt->bindValue(':organization_id', $organizationId->toString());
            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @param UserId $userId
     * @param OrganizationId $organizationId
     *
     * @return bool
     */
    public function userExistsInOrganization(UserId $userId, OrganizationId $organizationId): bool
    {
        $sql = <<<SQL
        SELECT COUNT(*) 
        FROM users_organizations 
        WHERE user_id = :user_id AND organization_id = :organization_id
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId->toString());
        $stmt->bindValue(':organization_id', $organizationId->toString());
        $stmt->execute();

        return (bool)$stmt->fetchColumn();
    }
}
