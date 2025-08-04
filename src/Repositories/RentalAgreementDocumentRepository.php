<?php

declare(strict_types=1);

namespace src\Repositories;

use DateTimeImmutable;
use PDOException;
use src\Exceptions\RentalAgreementException;
use src\Exceptions\ServerException;
use src\Factories\RentalAgreementDocumentFactory;
use src\Models\RentalAgreementDocument;
use PDO;
use Throwable;

final readonly class RentalAgreementDocumentRepository
{
    public function __construct(
        private PDO $db,
        private RentalAgreementDocumentFactory $factory
    ) {
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     */
    public function findById(int $id): RentalAgreementDocument
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM rental_agreements_documents WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_DOCUMENT_NOT_FOUND);
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
            $stmt = $this->db->query('SELECT * FROM rental_agreements_documents');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'hydrate'], $data);
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     * @throws RentalAgreementException
     */
    public function create(RentalAgreementDocument $rentalAgreementDocument): RentalAgreementDocument
    {
        try {
            $sql = <<<'SQL'
            INSERT INTO rental_agreements_documents (
                                                     rental_agreement_id, 
                                                     file_name, 
                                                     file_path, 
                                                     file_type, 
                                                     uploaded_at
                                                     )
            VALUES (
                :rental_agreement_id, 
                :file_name, 
                :file_path, 
                :file_type, 
                :uploaded_at
            )
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':rental_agreement_id', $rentalAgreementDocument->rentalAgreementId, PDO::PARAM_INT);
            $stmt->bindValue(':file_name', $rentalAgreementDocument->fileName);
            $stmt->bindValue(':file_path', $rentalAgreementDocument->filePath);
            $stmt->bindValue(':file_type', $rentalAgreementDocument->fileType);
            $stmt->bindValue(':uploaded_at', $rentalAgreementDocument->uploadedAt->format('Y-m-d H:i:s'));

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_DOCUMENT_CREATE_FAILED);
            }

            return $this->factory->withId(
                $rentalAgreementDocument,
                (int)$this->db->lastInsertId()
            );
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    public function update(RentalAgreementDocument $rentalAgreementDocument): void
    {
        try {
            $sql = <<<'SQL'
            UPDATE rental_agreements_documents
            SET rental_agreement_id = :rental_agreement_id,
                file_name = :file_name,
                file_path = :file_path,
                file_type = :file_type,
                uploaded_at = :uploaded_at
            WHERE id = :id
            SQL;

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $rentalAgreementDocument->id, PDO::PARAM_INT);
            $stmt->bindValue(':rental_agreement_id', $rentalAgreementDocument->rentalAgreementId, PDO::PARAM_INT);
            $stmt->bindValue(':file_name', $rentalAgreementDocument->fileName);
            $stmt->bindValue(':file_path', $rentalAgreementDocument->filePath);
            $stmt->bindValue(':file_type', $rentalAgreementDocument->fileType);
            $stmt->bindValue(':uploaded_at', $rentalAgreementDocument->uploadedAt->format('Y-m-d H:i:s'));

            $stmt->execute();
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     */
    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM rental_agreements_documents WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new RentalAgreementException(RentalAgreementException::RENTAL_AGREEMENT_DOCUMENT_NOT_FOUND);
            }
        } catch (PDOException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @throws ServerException
     */
    private function hydrate(array $data): RentalAgreementDocument
    {
        try {
            return new RentalAgreementDocument(
                id: (int)$data['id'],
                rentalAgreementId: (int)$data['rental_agreement_id'],
                fileName: $data['file_name'],
                filePath: $data['file_path'],
                fileType: $data['file_type'],
                uploadedAt: new DateTimeImmutable($data['uploaded_at'])
            );
        } catch (Throwable $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
