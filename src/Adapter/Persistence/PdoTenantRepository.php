<?php

declare(strict_types=1);

namespace Adapter\Persistence;

use DateTimeImmutable;
use PDOException;
use Shared\Exception\BaseException;
use Shared\Exception\ServerException;
use Domain\Exception\TenantException;
use Domain\Factory\TenantFactory;
use src\Entity\Tenant;
use PDO;
use Throwable;

final readonly class PdoTenantRepository
{
    public function __construct(
        private PDO $db,
        private TenantFactory $factory,
    ) {
    }

    /**
     * @throws TenantException
     * @throws ServerException
     */
    public function findById(int $id): Tenant
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM tenants WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new TenantException(TenantException::NOT_FOUND);
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
            $stmt = $this->db->query('SELECT * FROM tenants');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws TenantException
     * @throws ServerException
     */
    public function create(Tenant $tenant): Tenant
    {
        try {
            $sql = <<<'SQL'
                INSERT INTO tenants 
                    (first_name, last_name, email, phone, created_at, updated_at)
                VALUES
                    (:first_name, :last_name, :email, :phone, :created_at, :updated_at)
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':first_name', $tenant->firstName);
            $stmt->bindValue(':last_name', $tenant->lastName);
            $stmt->bindValue(':email', $tenant->email);
            $stmt->bindValue(':phone', $tenant->phone);
            $stmt->bindValue(':created_at', $tenant->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $tenant->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new TenantException(TenantException::CREATE_FAILED);
            }
            return $this->factory->withId($tenant, (int)$this->db->lastInsertId());
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    public function update(Tenant $tenant): void
    {
        try {
            $sql = <<<'SQL'
                UPDATE tenants
                SET first_name = :first_name,
                    last_name = :last_name,
                    email = :email,
                    phone = :phone,
                    updated_at = :updated_at
                WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $tenant->id, PDO::PARAM_INT);
            $stmt->bindValue(':first_name', $tenant->firstName);
            $stmt->bindValue(':last_name', $tenant->lastName);
            $stmt->bindValue(':email', $tenant->email);
            $stmt->bindValue(':phone', $tenant->phone);
            $stmt->bindValue(':updated_at', $tenant->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws TenantException
     * @throws ServerException
     */
    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM tenants WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new TenantException(TenantException::NOT_FOUND);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }


    /**
     * @throws TenantException
     */
    public function requireId(int $id): Tenant
    {
        try {
            return $this->findById($id);
        } catch (BaseException) {
            throw new TenantException(TenantException::NOT_FOUND);
        }
    }

    /**
     * @throws ServerException
     */
    private function hydrate(array $data): Tenant
    {
        try {
            return new Tenant(
                id: (int)$data['id'],
                firstName: $data['first_name'],
                lastName: $data['last_name'],
                email: $data['email'],
                phone: $data['phone'],
                createdAt: new DateTimeImmutable($data['created_at']),
                updatedAt: new DateTimeImmutable($data['updated_at'])
            );
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
