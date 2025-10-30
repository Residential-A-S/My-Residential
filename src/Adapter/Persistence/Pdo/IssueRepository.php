<?php

declare(strict_types=1);

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Application\Port\IssueRepository as IssueRepositoryInterface;
use DateTimeImmutable;
use Domain\Entity\Issue;
use Domain\Types\IssueStatus;
use Domain\ValueObject\IssueId;
use Domain\ValueObject\PaymentId;
use Domain\ValueObject\RentalAgreementId;
use PDO;
use PDOException;
use Throwable;

/**
 * PDO-backed implementation of the IssueRepository interface.
 *
 * Responsible for persisting and retrieving Issue entities using a relational database.
 */
final readonly class IssueRepository implements IssueRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(
        private PDO $db
    ) {
    }

    /**
     * @throws DatabaseException
     */
    public function findById(IssueId $id): Issue
    {
        try {
            $sql = 'SELECT * FROM issues WHERE id = :id';
            $stmt = $this->db->prepare($sql);
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
     * @throws DatabaseException
     */
    public function findAll(): array
    {
        try {
            $sql = 'SELECT * FROM issues';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    public function save(Issue $issue): void
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO issues (
                id, rental_agreement_id, payment_id, name, description, status, created_at, updated_at
            ) VALUES (
                :id, :rental_agreement_id, :payment_id, :name, :description, :status, :created_at, :updated_at
            )
            ON DUPLICATE KEY UPDATE
                rental_agreement_id = VALUES(rental_agreement_id),
                payment_id = VALUES(payment_id),
                name = VALUES(name),
                description = VALUES(description),
                status = VALUES(status),
                updated_at = VALUES(updated_at)
            SQL;
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $issue->id->toString());
            $stmt->bindValue(':rental_agreement_id', $issue->rentalAgreementId->toString());
            $stmt->bindValue(
                ':payment_id',
                $issue->paymentId?->toString(),
                $issue->paymentId === null ? PDO::PARAM_NULL : PDO::PARAM_STR
            );
            $stmt->bindValue(':name', $issue->name);
            $stmt->bindValue(':description', $issue->description);
            $stmt->bindValue(':status', $issue->status);
            $stmt->bindValue(':created_at', $issue->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $issue->updatedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @throws DatabaseException
     */
    public function delete(IssueId $id): void
    {
        try {
            $sql = 'DELETE FROM issues WHERE id = :id';
            $stmt = $this->db->prepare($sql);
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
     *
     * @param array{
     *     id:string,
     *     rental_agreement_id:string,
     *     payment_id:string|null,
     *     name:string,
     *     description:string,
     *     status:string,
     *     created_at:string,
     *     updated_at:string
     * } $data
     * @return Issue
     * @throws DatabaseException
     */
    private function hydrate(array $data): Issue
    {
        try {
            return new Issue(
                id:                 new IssueId($data['id']),
                rentalAgreementId:  new RentalAgreementId($data['rental_agreement_id']),
                paymentId:          $data['payment_id'] === null ? null : new PaymentId($data['payment_id']),
                name:               $data['name'],
                description:        $data['description'],
                status:             IssueStatus::from($data['status']),
                createdAt:          new DateTimeImmutable($data['created_at']),
                updatedAt:          new DateTimeImmutable($data['updated_at'])
            );
        } catch (Throwable) {
            throw new DatabaseException(DatabaseException::HYDRATION_FAILED);
        }
    }
}
