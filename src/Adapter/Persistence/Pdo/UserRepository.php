<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Application\Port\UserRepository as UserRepositoryInterface;
use DateTimeImmutable;
use Domain\Entity\User;
use Domain\ValueObject\Email;
use Domain\ValueObject\PasswordHash;
use Domain\ValueObject\UserId;
use PDO;
use PDOException;
use Throwable;

/**
 *
 */
final readonly class UserRepository implements UserRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(
        private PDO $db
    ) {
    }


    /**
     * @param UserId $id
     *
     * @return User
     * @throws DatabaseException
     */
    public function findById(UserId $id): User
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
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
     * @param Email $email
     *
     * @return User
     * @throws DatabaseException
     */
    public function findByEmail(Email $email): User
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->bindValue(':email', $email->value);
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
     * @return User[]
     * @throws DatabaseException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users');
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param User $user
     *
     * @return void
     * @throws DatabaseException
     */
    public function save(User $user): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO users (
                id, email, password_hash, name, created_at, updated_at, last_login_at, failed_attempts
            ) VALUES (
                :id, :email, :password_hash, :name, :created_at, :updated_at, :last_login_at, :failed_attempts
            )
            ON DUPLICATE KEY UPDATE 
                email = :email,
                password_hash = :password_hash,
                name = :name,
                updated_at = :updated_at,
                last_login_at = :last_login_at,
                failed_attempts = :failed_attempts
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $user->id->toString());
            $stmt->bindValue(':email', $user->email->value);
            $stmt->bindValue(':password_hash', $user->passwordHash);
            $stmt->bindValue(':name', $user->name);
            $stmt->bindValue(':created_at', $user->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $user->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':last_login_at', $user->lastLoginAt?->format('Y-m-d H:i:s'));
            $stmt->bindValue(':failed_attempts', $user->failedLoginAttempts, PDO::PARAM_INT);

            if( $user->id !== null) {
                $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
            }

            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }


    /**
     * @param UserId $id
     *
     * @return void
     * @throws DatabaseException
     */
    public function delete(UserId $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
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
     * @param Email $email
     *
     * @return bool
     */
    public function existsByEmail(Email $email): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email->value);
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }


    /**
     * @param UserId $id
     *
     * @return bool
     */
    public function existsById(UserId $id): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id->toString());
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }


    /**
     * @param array{
     *     id: int,
     *     email: string,
     *     password_hash: string,
     *     name: string,
     *     created_at: string,
     *     updated_at: string,
     *     last_login_at: string|null,
     *     failed_attempts: int
     * } $data
     *
     * @return User
     * @throws DatabaseException
     */
    private function hydrate(array $data): User
    {
        try {
            return new User(
                id: new UserId($data['id']),
                email: new Email($data['email']),
                passwordHash: new PasswordHash($data['password_hash']),
                name: $data['name'],
                createdAt: new DateTimeImmutable($data['created_at']),
                updatedAt: new DateTimeImmutable($data['updated_at']),
                lastLoginAt: $data['last_login_at'] !== null ? new DateTimeImmutable($data['last_login_at']) : null,
                failedLoginAttempts: (int)$data['failed_attempts']
            );
        } catch (Throwable) {
            throw new DatabaseException(DatabaseException::HYDRATION_FAILED);
        }
    }
}
