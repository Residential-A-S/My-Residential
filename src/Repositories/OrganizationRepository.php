<?php

namespace src\Repositories;

use DateTimeImmutable;
use PDO;
use PDOException;
use src\Exceptions\OrganizationException;
use src\Exceptions\ServerException;
use src\Factories\OrganizationFactory;
use src\Models\Organization;
use Throwable;

final readonly class OrganizationRepository
{
    public function __construct(
        private PDO $db,
        private OrganizationFactory $factory
    ) {
    }

    /**
     * @throws OrganizationException
     * @throws ServerException
     */
    public function findById(int $id): Organization
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM organizations WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                throw new OrganizationException(OrganizationException::NOT_FOUND);
            }
            return $this->hydrate($data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @return Organization[]
     * @throws ServerException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM organizations');
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws OrganizationException
     * @throws ServerException
     */
    public function create(Organization $organization): Organization
    {
        try {
            $sql = <<<'SQL'
                INSERT INTO organizations
                    (name, created_at, updated_at)
                VALUES 
                    (:name, :created_at, :updated_at)
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':name', $organization->name);
            $stmt->bindValue(':created_at', $organization->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $organization->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new OrganizationException(OrganizationException::CREATE_FAILED);
            }
            return $this->factory->withId($organization, (int)$this->db->lastInsertId());
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws OrganizationException
     * @throws ServerException
     */
    public function update(Organization $organization): void
    {
        try {
            $sql = <<<'SQL'
                UPDATE organizations
                SET name = :name, 
                    updated_at = :updated_at
                WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $organization->id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $organization->name);
            $stmt->bindValue(':updated_at', $organization->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws OrganizationException
     * @throws ServerException
     */
    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM organizations WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new OrganizationException(OrganizationException::DELETE_FAILED);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    public function existsById(int $id): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM organizations WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }

    /**
     * @throws OrganizationException
     * @throws ServerException
     */
    public function requireById(int $id): Organization
    {
        return $this->findById($id);
    }

    /**
     * Hydrates an array of data into an Organization object.
     *
     * @param array{
     *     id:int,
     *     name:string,
     *     created_at:string,
     *     updated_at:string
     * } $data
     * @return Organization
     * @throws ServerException
     */
    private function hydrate(array $data): Organization {
        try {
            return new Organization(
                $data['id'],
                $data['name'],
                new DateTimeImmutable($data['created_at']),
                new DateTimeImmutable($data['updated_at'])
            );
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }
}