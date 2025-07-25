<?php

namespace src\Repositories;

use PDO;
use src\Exceptions\ServerException;
use src\Models\ModelInterface;
use src\Models\Organization;

final readonly class OrganizationRepository
{
    public function __construct(
        private PDO $db,
    ) {}

    public function findById(int $id): Organization {
        $stmt = $this->db->prepare('SELECT * FROM organizations WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new ServerException(ServerException::ORGANIZATION_FIND_FAILED);
        }
        return $this->hydrate($data);
    }

    /**
     * @return Organization[]
     */
    public function findAll(): array {
        $stmt = $this->db->prepare('SELECT * FROM organizations');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrate'], $data);
    }

    public function findAllForCurrentUser(int $userId): array
    {
        $sql = <<<SQL
        SELECT * FROM organizations as o
        LEFT JOIN user_organization_relations uor on o.id = uor.organization_id
        WHERE uor.user_id = :user_id
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrate'], $data);
    }

    public function hydrate(array $data): Organization {
        return new Organization(
            $data['id'],
            $data['name'],
            $data['description']
        );
    }

    public function create(string $name, string $description, int $userId): Organization {
        $stmt = $this->db->prepare('INSERT INTO organizations (name, description) VALUES (:name, :description)');
        $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);
        if( $stmt->rowCount() === 0) {
            throw new ServerException(ServerException::ORGANIZATION_CREATE_FAILED);
        }

        $org = $this->findById((int)$this->db->lastInsertId());
        $stmt = $this->db->prepare('INSERT INTO user_organization_relations (organization_id, user_id) VALUES (:organization_id, :user_id)');
        $stmt->execute([
            ':organization_id' => $org->id,
            ':user_id' => $userId
        ]);
        if( $stmt->rowCount() === 0) {
            $this->delete($org->id);
            throw new ServerException(ServerException::ORGANIZATION_CREATE_FAILED);
        }
        return $org;
    }

    /**
     * @param Organization $entity
     * @return void
     */
    public function update(ModelInterface $entity): void {
        $stmt = $this->db->prepare('UPDATE organizations SET name = :name, description = :description WHERE id = :id');
        $stmt->execute([
            ':id' => $entity->id,
            ':name' => $entity->name,
            ':description' => $entity->description
        ]);
        if ($stmt->rowCount() === 0) {
            throw new ServerException(ServerException::ORGANIZATION_UPDATE_FAILED);
        }
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('DELETE FROM organizations WHERE id = :id');
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() === 0) {
            throw new ServerException(ServerException::ORGANIZATION_DELETE_FAILED);
        }
    }
}