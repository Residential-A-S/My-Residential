<?php

namespace Adapter\Persistence;

use Application\Port\OrganizationRepository;
use DateTimeImmutable;
use PDO;
use PDOException;
use Domain\Exception\OrganizationException;
use Shared\Exception\ServerException;
use Domain\Factory\OrganizationFactory;
use src\Entity\Organization;
use Throwable;

final readonly class PdoOrganizationRepository implements OrganizationRepository
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
    public function save(Organization $organization): Organization
    {
        try {
            if ($organization->id === null) {
                $sql = <<<'SQL'
                INSERT INTO organizations
                    (name, created_at, updated_at)
                VALUES 
                    (:name, :created_at, :updated_at)
                SQL;
            } else {
                $sql = <<<'SQL'
                UPDATE organizations
                SET name = :name, 
                    updated_at = :updated_at
                WHERE id = :id
                SQL;
            }

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':name', $organization->name);
            $stmt->bindValue(':created_at', $organization->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $organization->updatedAt->format('Y-m-d H:i:s'));

            if ($organization->id !== null) {
                $stmt->bindValue(':id', $organization->id, PDO::PARAM_INT);
            }

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new OrganizationException(OrganizationException::CREATE_FAILED);
            }

            if ($organization->id !== null) {
                return $organization;
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

    /**
     * Hydrates an array of data into an CreateOrganizationCommand object.
     *
     * @param array{
     *     id:int,
     *     name:string,
     *     created_at:string,
     *     updated_at:string
     * } $data
     * @return CreateOrganizationCommand
     * @throws ServerException
     */
    private function hydrate(array $data): Organization
    {
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
