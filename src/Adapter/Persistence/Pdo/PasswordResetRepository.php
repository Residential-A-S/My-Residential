<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Application\Port\PasswordResetRepository as PasswordResetRepositoryInterface;
use DateTimeImmutable;
use Domain\Entity\PasswordReset;
use Domain\ValueObject\PasswordResetId;
use Domain\ValueObject\PasswordResetToken;
use Domain\ValueObject\UserId;
use PDO;
use PDOException;
use Throwable;

/**
 *
 */
final readonly class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(private PDO $db) {}

    /**
     * @throws DatabaseException
     */
    public function savePasswordResetToken(PasswordReset $passwordReset): void
    {
        try{
            $sql  = <<<'SQL'
            INSERT INTO password_resets (id, user_id, token, expires_at, created_at)
            VALUES (:id, :userId, :hashedToken, :expiresAt, :createdAt)
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $passwordReset->id->toString());
            $stmt->bindValue(':userId', $passwordReset->userId->toString());
            $stmt->bindValue(':hashedToken', $passwordReset->token);
            $stmt->bindValue(':expiresAt', $passwordReset->expiresAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':createdAt', $passwordReset->createdAt->format('Y-m-d H:i:s'));
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new DatabaseException(DatabaseException::QUERY_FAILED);
            }
        }catch (PDOException){
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @param PasswordResetToken $token
     *
     * @return PasswordReset
     * @throws DatabaseException
     */
    public function findByToken(PasswordResetToken $token): PasswordReset
    {
        try{
            $sql  = <<<'SQL'
            SELECT user_id, token, expires_at, created_at
            FROM password_resets
            WHERE token = :token
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':token', $token->value);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( ! $data) {
                throw new DatabaseException(DatabaseException::RECORD_NOT_FOUND);
            }

            return $this->hydrate($data);
        }catch (PDOException){
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    public function deleteByToken(PasswordResetToken $token): void
    {
        try{
            $sql  = <<<'SQL'
            DELETE FROM password_resets
            WHERE token = :token
            SQL;
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':token', $token->value);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new DatabaseException(DatabaseException::RECORD_NOT_FOUND);
            }
        }catch (PDOException){
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    private function hydrate(array $data): PasswordReset
    {
        try{
            return new PasswordReset(
                id: new PasswordResetId($data['id']),
                userId: new UserId($data['user_id']),
                token: new PasswordResetToken($data['token']),
                expiresAt: new DateTimeImmutable($data['expires_at']),
                createdAt: new DateTimeImmutable($data['created_at']),
            );
        }catch (Throwable){
            throw new DatabaseException(DatabaseException::HYDRATION_FAILED);
        }
    }
}