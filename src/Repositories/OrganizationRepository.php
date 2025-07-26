<?php

namespace src\Repositories;

use PDO;
use src\Exceptions\ServerException;
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
            $data['name']
        );
    }

    public function create(string $name): Organization {
        $stmt = $this->db->prepare('INSERT INTO organizations (name) VALUES (:name)');
        $stmt->execute([':name' => $name]);
        if( $stmt->rowCount() === 0) {
            throw new ServerException(ServerException::ORGANIZATION_CREATE_FAILED);
        }
        return $this->hydrate([
            'id' => (int)$this->db->lastInsertId(),
            'name' => $name
        ]);
    }


    public function update(Organization $org): void {
        $stmt = $this->db->prepare('UPDATE organizations SET name = :name WHERE id = :id');
        $stmt->execute([
            ':id' => $org->id,
            ':name' => $org->name
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