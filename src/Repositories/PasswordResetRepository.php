<?php

declare(strict_types=1);

namespace src\Repositories;

use DateMalformedStringException;
use DateTime;
use src\Exceptions\PasswordResetException;
use src\Exceptions\ServerException;
use PDO;
use PDOException;

final readonly class PasswordResetRepository
{
    public function __construct(private PDO $db) {}

    /**
     * @throws PasswordResetException
     * @throws ServerException
     */
    public function insertPasswordResetToken(int $userId, string $hashedToken, DateTime $expiresAt): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO password_resets (user_id, token, expires_at)
            VALUES (:userId, :hashedToken, :expiresAt)
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':hashedToken', $hashedToken);
            $stmt->bindValue(':expiresAt', $expiresAt->format('Y-m-d H:i:s'));
            $stmt->execute();

            if($stmt->rowCount() === 0) {
                throw new PasswordResetException(PasswordResetException::INSERT_FAILED);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @param string $token
     * @return array{ user_id: int, token: string, expires_at: DateTime }
     * @throws ServerException
     * @throws PasswordResetException
     */
    public function findByToken(string $token): array
    {
        try {
            $sql = <<<'SQL'
            SELECT user_id, token, expires_at
            FROM password_resets
            WHERE token = :token
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':token', $token);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new PasswordResetException(PasswordResetException::INVALID_TOKEN);
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $row['expires_at'] = new DateTime($row['expires_at']);

            return $row;
        } catch (PDOException|DateMalformedStringException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     * @throws PasswordResetException
     */
    public function deleteByToken(string $hashedToken): void
    {
        try {
            $sql = <<<'SQL'
            DELETE FROM password_resets
            WHERE token = :hashedToken
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':hashedToken', $hashedToken);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new PasswordResetException(PasswordResetException::DELETE_FAILED);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }
}