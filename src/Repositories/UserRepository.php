<?php

declare(strict_types=1);

namespace src\Repositories;

use src\Exceptions\ServerException;
use src\Models\ModelInterface;
use src\Models\User;
use PDO;
use PDOException;

final readonly class UserRepository
{
    public function __construct(private PDO $db) {}

    /** Finds a user by their ID. */
    public function findById(int $id): User
    {
        try {
            $sql = <<<'SQL'
            SELECT id, email, password
            FROM users
            WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new ServerException(ServerException::USER_FIND_FAILED);
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $this->hydrate($row);
        } catch (PDOException) {
            throw new ServerException(ServerException::UNKNOWN_ERROR);
        }
    }

    /** Finds a user by their email. */
    public function findByEmail(string $email): User
    {
        try{
            $sql = <<<'SQL'
            SELECT id, email, password
            FROM users
            WHERE email = :email
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new ServerException(ServerException::USER_FIND_FAILED);
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $this->hydrate($row);
        } catch (PDOException) {
            throw new ServerException(ServerException::UNKNOWN_ERROR);
        }
    }

    /** Finds all users*/
    public function findAll(): array
    {
        try{
            $sql = <<<'SQL'
            SELECT id, email, password
            FROM users
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map([$this, 'hydrate'], $rows);
        } catch (PDOException) {
            throw new ServerException(ServerException::UNKNOWN_ERROR);
        }
    }

    /** @param array{id:int, email:string, password:string} $data */
    public function hydrate(array $data): User
    {
        return new User(
            id: (int)$data['id'],
            email: (string)$data['email'],
            password: (string)$data['password']
        );
    }

    /**
     * @param User $entity
     * @return void
     */
    public function update(ModelInterface $entity): void
    {
        try {
            $sql = <<<'SQL'
            UPDATE users
            SET 
                email = :email,
                password = :password
            WHERE id = :id
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $entity->id, PDO::PARAM_INT);
            $stmt->bindValue(':username', $entity->email);
            $stmt->bindValue(':password', password_hash($entity->password, PASSWORD_DEFAULT));
            $stmt->execute();

            if($stmt->rowCount() === 0) {
                throw new ServerException(ServerException::USER_UPDATE_FAILED);
            }
        } catch (PDOException) {
            throw new ServerException(ServerException::UNKNOWN_ERROR);
        }
    }

    /** @param array{email:string, password:string} $data */
    public function create(array $data): User
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO users (email, password)
            VALUES (:email, :password)
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':password', $data['password']);
            $stmt->execute();

            if($stmt->rowCount() === 0) {
                throw new ServerException(ServerException::USER_CREATE_FAILED);
            }

            $data = $this->findById((int) $this->db->lastInsertId());
            $stmt->closeCursor();
            return $data;
        } catch (PDOException) {
            throw new ServerException(ServerException::UNKNOWN_ERROR);
        }
    }

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
                throw new ServerException(ServerException::USER_DELETE_FAILED);
            }

            $stmt->closeCursor();
        } catch (PDOException) {
            throw new ServerException(ServerException::UNKNOWN_ERROR);
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