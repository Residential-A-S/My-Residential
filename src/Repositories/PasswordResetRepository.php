<?php

declare(strict_types=1);

namespace src\Repositories;

use DateMalformedStringException;
use DateTime;
use DateTimeImmutable;
use src\Exceptions\PasswordResetException;
use src\Exceptions\PaymentException;
use src\Exceptions\ServerException;
use PDO;
use PDOException;
use src\Models\PasswordReset;

final readonly class PasswordResetRepository
{
    public function __construct(private PDO $db)
    {
    }

    /**
     * @throws PasswordResetException
     * @throws ServerException
     */
    public function insertPasswordResetToken(
        int $userId,
        string $hashedToken,
        DateTimeImmutable $expiresAt,
        DateTimeImmutable $createdAt
    ): void {
        try {
            $sql = <<<'SQL'
            INSERT INTO password_resets (user_id, token, expires_at, created_at)
            VALUES (:userId, :hashedToken, :expiresAt, :createdAt)
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':hashedToken', $hashedToken);
            $stmt->bindValue(':expiresAt', $expiresAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':createdAt', $createdAt->format('Y-m-d H:i:s'));
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new PasswordResetException(PasswordResetException::INSERT_FAILED);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @param string $token
     * @return PasswordReset
     * @throws ServerException
     * @throws PasswordResetException
     */
    public function findByToken(string $token): PasswordReset
    {
        try {
            $sql = <<<'SQL'
            SELECT user_id, token, expires_at, created_at
            FROM password_resets
            WHERE token = :token
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':token', $token);
            $stmt->execute();


            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                throw new PasswordResetException(PasswordResetException::INVALID_TOKEN);
            }

            return $this->hydrate($data);
        } catch (PDOException $e) {
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

    /**
     * @throws ServerException
     */
    private function hydrate(array $data): PasswordReset
    {
        try {
            return new PasswordReset(
                userId: (int)$data['user_id'],
                token: $data['token'],
                expiresAt: new DateTimeImmutable($data['expires_at']),
                createdAt: new DateTimeImmutable($data['created_at']),
            );
        } catch (DateMalformedStringException $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
