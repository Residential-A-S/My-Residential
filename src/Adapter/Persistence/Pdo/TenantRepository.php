<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use DateTimeImmutable;
use Application\Port\TenantRepository as TenantRepositoryInterface;
use Domain\ValueObject\Email;
use Domain\ValueObject\Phone;
use Domain\ValueObject\TenantId;
use PDO;
use PDOException;
use Domain\Entity\Tenant;
use Throwable;

/**
 *
 */
final readonly class TenantRepository implements TenantRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(
        private PDO $db
    ) {
    }

    /**
     * @param TenantId $id
     *
     * @return Tenant
     * @throws DatabaseException
     */
    public function findById(TenantId $id): Tenant
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM tenants WHERE id = :id');
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
     * @return Tenant[]
     * @throws DatabaseException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT * FROM tenants');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param Tenant $tenant
     *
     * @return void
     * @throws DatabaseException
     */
    public function save(Tenant $tenant): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO tenants (
                id, first_name, last_name, email, phone, created_at, updated_at
            ) VALUES (
                :id, :first_name, :last_name, :email, :phone, :created_at, :updated_at
            )
            ON DUPLICATE KEY UPDATE 
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone = :phone,
                updated_at = :updated_at
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $tenant->id->toString());
            $stmt->bindValue(':first_name', $tenant->firstName);
            $stmt->bindValue(':last_name', $tenant->lastName);
            $stmt->bindValue(':email', $tenant->email->value);
            $stmt->bindValue(':phone', $tenant->phone->value);
            $stmt->bindValue(':created_at', $tenant->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $tenant->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param int $id
     *
     * @return void
     * @throws DatabaseException
     */
    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM tenants WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new DatabaseException(DatabaseException::RECORD_NOT_FOUND);
            }
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param array{
     *     id: string,
     *     first_name: string,
     *     last_name: string,
     *     email: string,
     *     phone: string,
     *     created_at: string,
     *     updated_at: string
     * } $data
     *
     * @return Tenant
     * @throws DatabaseException
     */
    private function hydrate(array $data): Tenant
    {
        try {
            return new Tenant(
                id: new TenantId($data['id']),
                firstName: $data['first_name'],
                lastName: $data['last_name'],
                email: new Email($data['email']),
                phone: new Phone($data['phone']),
                createdAt: new DateTimeImmutable($data['created_at']),
                updatedAt: new DateTimeImmutable($data['updated_at'])
            );
        } catch (Throwable) {
            throw new DatabaseException(DatabaseException::HYDRATION_FAILED);
        }
    }
}
