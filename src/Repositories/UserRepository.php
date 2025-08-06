<?php

declare(strict_types=1);

namespace src\Repositories;

use DateTimeImmutable;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Factories\UserFactory;
use src\Models\User;
use PDO;
use PDOException;
use Throwable;

final readonly class UserRepository
{
    public function __construct(
        private PDO $db,
        private UserFactory $factory,
    ) {
    }

    /** Finds a user by their ID.
     *
     * @throws ServerException
     * @throws UserException
     */
    public function findById(int $id): User
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                throw new UserException(UserException::NOT_FOUND);
            }
            return $this->hydrate($data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /** Finds a user by their email.
     *
     * @throws ServerException
     * @throws UserException
     */
    public function findByEmail(string $email): User
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                throw new UserException(UserException::NOT_FOUND);
            }
            return $this->hydrate($data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /** Finds all users*
     *
     * @throws ServerException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users');
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws UserException
     * @throws ServerException
     */
    public function create(User $user): User
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO users 
                (email, password_hash, name, created_at, updated_at, last_login_at, failed_attempts)
            VALUES 
                (:email, :password_hash, :name, :created_at, :updated_at, :last_login_at, :failed_attempts)
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':email', $user->email);
            $stmt->bindValue(':password_hash', $user->passwordHash);
            $stmt->bindValue(':name', $user->name);
            $stmt->bindValue(':created_at', $user->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $user->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':last_login_at', $user->lastLoginAt?->format('Y-m-d H:i:s'));
            $stmt->bindValue(':failed_attempts', $user->failedLoginAttempts, PDO::PARAM_INT);

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new UserException(UserException::CREATE_FAILED);
            }
            return $this->factory->withId($user, (int)$this->db->lastInsertId());
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @param User $user
     * @throws ServerException
     */
    public function update(User $user): void
    {
        try {
            $sql = <<<'SQL'
            UPDATE users
            SET 
                email = :email,
                password_hash = :passwordHash,
                name = :name,
                updated_at = :updatedAt,
                last_login_at = :lastLoginAt,
                failed_attempts = :failedAttempts            
            WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
            $stmt->bindValue(':email', $user->email);
            $stmt->bindValue(':passwordHash', $user->passwordHash);
            $stmt->bindValue(':name', $user->name);
            $stmt->bindValue(':updatedAt', $user->updatedAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':lastLoginAt', $user->lastLoginAt?->format('Y-m-d H:i:s'));
            $stmt->bindValue(':failedAttempts', $user->failedLoginAttempts, PDO::PARAM_INT);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws UserException
     * @throws ServerException
     */
    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new UserException(UserException::NOT_FOUND);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /** Checks if a user exists by their email. */
    public function existsByEmail(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }

    /** Checks if a user exists by their ID. */
    public function existsById(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }

    /**
     * @throws UserException
     * @throws ServerException
     */
    public function requireById(int $id): User
    {
        return $this->findById($id);
    }

    /**
     * @throws ServerException
     */
    private function hydrate(array $data): User
    {
        try {
            $lastLoginAt = $data['last_login_at'] !== null ? new DateTimeImmutable($data['last_login_at']) : null;
            return new User(
                id: (int)$data['id'],
                email: $data['email'],
                passwordHash: $data['password'],
                name: $data['name'],
                createdAt: new DateTimeImmutable($data['created_at']),
                updatedAt: new DateTimeImmutable($data['updated_at']),
                lastLoginAt: $lastLoginAt,
                failedLoginAttempts: (int)$data['failed_attempts']
            );
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
