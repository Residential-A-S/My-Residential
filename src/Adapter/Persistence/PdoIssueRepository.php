<?php

declare(strict_types=1);

namespace Adapter\Persistence;

use Application\Port\IssueRepository;
use DateTimeImmutable;
use PDOException;
use Domain\Exception\IssueException;
use Shared\Exception\ServerException;
use src\Factories\IssueFactory;
use src\Entity\Issue;
use PDO;
use Throwable;

final readonly class PdoIssueRepository implements IssueRepository
{
    public function __construct(
        private PDO $db,
        private IssueFactory $factory,
    ) {
    }

    /**
     * @throws IssueException
     * @throws ServerException
     */
    public function findById(int $id): Issue
    {
        try {
            $sql = 'SELECT * FROM issues WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                throw new IssueException(IssueException::NOT_FOUND);
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
            $sql = 'SELECT * FROM issues';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     * @throws IssueException
     */
    public function save(Issue $issue): Issue
    {
        try {
            if ($issue->id === null) {
                $sql = <<<'SQL'
                INSERT INTO issues
                    (rental_agreement_id, payment_id, name, description, status, created_at, updated_at)
                VALUES
                    (:rental_agreement_id, :payment_id, :name, :description, :status, :created_at, :updated_at)
                SQL;
            } else {
                $sql = <<<'SQL'
                UPDATE issues
                SET rental_agreement_id = :rental_agreement_id,
                    payment_id = :payment_id,
                    name = :name,
                    description = :description,
                    status = :status,
                    updated_at = :updated_at
                WHERE id = :id
                SQL;
            }
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':rental_agreement_id', $issue->rentalAgreementId, PDO::PARAM_INT);
            $stmt->bindValue(
                ':payment_id',
                $issue->paymentId,
                $issue->paymentId === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            );
            $stmt->bindValue(':name', $issue->name);
            $stmt->bindValue(':description', $issue->description);
            $stmt->bindValue(':status', $issue->status);
            $stmt->bindValue(':created_at', $issue->createdAt->format('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', $issue->updatedAt->format('Y-m-d H:i:s'));

            if ($issue->id !== null) {
                $stmt->bindValue(':id', $issue->id, PDO::PARAM_INT);
            }

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new IssueException(IssueException::SAVE_FAILED);
            }
            if ($issue->id !== null) {
                return $issue;
            }
            return $this->factory->withId($issue, (int)$this->db->lastInsertId());
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     * @throws IssueException
     */
    public function delete(int $id): void
    {
        try {
            $sql = 'DELETE FROM issues WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new IssueException(IssueException::NOT_FOUND);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * Hydrates an array of data into an Issue object.
     *
     * @param array{
     *     id:int,
     *     rental_agreement_id:int,
     *     payment_id:int|null,
     *     name:string,
     *     description:string,
     *     status:string,
     *     created_at:string,
     *     updated_at:string
     * } $data
     * @return Issue
     * @throws ServerException
     */
    private function hydrate(array $data): Issue
    {
        try {
            return new Issue(
                id:                 $data['id'],
                rentalAgreementId:  $data['rental_agreement_id'],
                paymentId:          $data['payment_id'] === null ? null : (int)$data['payment_id'],
                name:               $data['name'],
                description:        $data['description'],
                status:             $data['status'],
                createdAt:          new DateTimeImmutable($data['created_at']),
                updatedAt:          new DateTimeImmutable($data['updated_at'])
            );
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
