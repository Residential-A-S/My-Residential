<?php

namespace Adapter\Persistence;

use Adapter\Exception\DatabaseException;
use Application\Port\OrganizationRepository;
use DateTimeImmutable;
use Domain\ValueObject\OrganizationId;
use PDO;
use PDOException;
use Throwable;
use Domain\Entity\Organization;

final readonly class PdoOrganizationRepository implements OrganizationRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    /**
     * @throws DatabaseException
     */
    public function findById(OrganizationId $id): Organization
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM organizations WHERE id = :id');
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
     * @return Organization[]
     * @throws DatabaseException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM organizations');
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
    public function save(Organization $organization): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO organizations(
              id, name, created_at, updated_at
            ) VALUES (
              :id, :name, :created_at, :updated_at
            )
            ON DUPLICATE KEY UPDATE 
                name = :name,
                updated_at = :updated_at
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $organization->id->toString());
            $stmt->bindValue(':name', $organization->name);
            $stmt->bindValue(':created_at', $organization->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $organization->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new DatabaseException(DatabaseException::QUERY_FAILED);
            }
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    public function delete(OrganizationId $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM organizations WHERE id = :id');
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
     * Hydrates an array of data into an CreateOrganizationCommand object.
     *
     * @param array{
     *     id:string,
     *     name:string,
     *     created_at:string,
     *     updated_at:string
     * } $data
     * @return Organization
     * @throws DatabaseException
     */
    private function hydrate(array $data): Organization
    {
        try {
            return new Organization(
                new OrganizationId($data['id']),
                $data['name'],
                new DateTimeImmutable($data['created_at']),
                new DateTimeImmutable($data['updated_at'])
            );
        } catch (Throwable) {
            throw new DatabaseException(DatabaseException::HYDRATION_FAILED);
        }
    }
}
