<?php

declare(strict_types=1);

namespace src\Repositories;

use DateMalformedStringException;
use DateTime;
use Exception;
use src\Enums\Role;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Models\User;
use PDO;
use PDOException;

final readonly class UserRepository
{
    public function __construct(private PDO $db) {}

    /** Finds a user by their ID.
     *
     * @throws ServerException
     * @throws UserException
     */
    public function findById(int $id): User
    {
        try {
            $sql = <<<'SQL'
            SELECT id, email, password, created_at, last_login_at, failed_attempts, name, role
            FROM users
            WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new UserException(UserException::FIND_FAILED);
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $this->hydrate($row);
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
        try{
            $sql = <<<'SQL'
            SELECT id, email, password, created_at, last_login_at, failed_attempts, name, role
            FROM users
            WHERE email = :email
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new UserException(UserException::FIND_FAILED);
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $this->hydrate($row);
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
        try{
            $sql = <<<'SQL'
            SELECT id, email, password, created_at, last_login_at, failed_attempts, name, role
            FROM users
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map([$this, 'hydrate'], $rows);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /** @param array{
     *     id:int,
     *     email:string,
     *     password:string,
     *     created_at:string,
     *     last_login_at:string,
     *     failed_attempts:int,
     *     name:string,
     *     role:string
     * } $data
     * @throws ServerException
     */
    public function hydrate(array $data): User
    {
        try {
            $createdAt = new DateTime($data['created_at']);
            $lastLoginAt = $data['last_login_at'] !== null ? new DateTime($data['last_login_at']) : null;
            $role = Role::from($data['role']);
            return new User(
                id: (int)$data['id'],
                email: (string)$data['email'],
                passwordHash: (string)$data['password'],
                createdAt: $createdAt,
                lastLoginAt: $lastLoginAt,
                failedLoginAttempts: (int)$data['failed_attempts'],
                name: (string)$data['name'],
                role: $role
            );
        } catch (DateMalformedStringException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws UserException
     * @throws ServerException
     */
    public function update(User $user): void
    {
        try {
            $sql = <<<'SQL'
            UPDATE users
            SET 
                email = :email,
                password = :passwordHash,
                created_at = :createdAt,
                last_login_at = :lastLoginAt,
                failed_attempts = :failedAttempts,
                name = :name,
                role = :role
            WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
            $stmt->bindValue(':email', $user->email);
            $stmt->bindValue(':passwordHash', $user->passwordHash);
            $stmt->bindValue(':createdAt', $user->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':lastLoginAt', $user->lastLoginAt?->format('Y-m-d H:i:s'));
            $stmt->bindValue(':failedAttempts', $user->failedLoginAttempts, PDO::PARAM_INT);
            $stmt->bindValue(':name', $user->name);
            $stmt->bindValue(':role', $user->role->to());
            $stmt->execute();

            if($stmt->rowCount() === 0) {
                throw new UserException(UserException::UPDATE_FAILED);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws UserException
     * @throws ServerException
     */
    public function create(string $email, string $hashedPassword, string $name, Role $role): User
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO users (email, password, name, role)
            VALUES (:email, :password, :name, :role)
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $hashedPassword);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':role', $role->to());
            $stmt->execute();

            if($stmt->rowCount() === 0) {
                throw new UserException(UserException::CREATE_FAILED);
            }

            $data = $this->findById((int) $this->db->lastInsertId());
            $stmt->closeCursor();
            return $data;
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
            $sql = <<<'SQL'
            DELETE FROM users
            WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if($stmt->rowCount() === 0) {
                throw new UserException(UserException::DELETE_FAILED);
            }

            $stmt->closeCursor();
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
}