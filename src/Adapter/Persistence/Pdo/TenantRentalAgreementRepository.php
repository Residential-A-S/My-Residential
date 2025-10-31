<?php

namespace Adapter\Persistence\Pdo;

use Adapter\Exception\DatabaseException;
use Application\Port\TenantRentalAgreementRepository as TenantRentalAgreementRepositoryInterface;
use Domain\ValueObject\RentalAgreementId;
use Domain\ValueObject\TenantId;
use PDO;
use PDOException;

/**
 *
 */
final readonly class TenantRentalAgreementRepository implements TenantRentalAgreementRepositoryInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(
        private PDO $db
    ) {
    }

    /**
     * @param TenantId $tenantId
     * @param RentalAgreementId $rentalAgreementId
     *
     * @return void
     * @throws DatabaseException
     */
    public function addUserToRentalAgreement(TenantId $tenantId, RentalAgreementId $rentalAgreementId): void
    {
        try{
            $sql = <<<SQL
        INSERT INTO tenants_rental_agreements (tenant_id, rental_agreement_id) 
        VALUES (:tenant_id, :rental_agreement_id)
        SQL;

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':tenant_id', $tenantId->toString());
            $stmt->bindValue(':rental_agreement_id', $rentalAgreementId->toString());
            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }

    /**
     * @param TenantId $tenantId
     * @param RentalAgreementId $rentalAgreementId
     *
     * @return void
     * @throws DatabaseException
     */
    public function removeTenantFromRentalAgreement(TenantId $tenantId, RentalAgreementId $rentalAgreementId): void
    {
        try{
            $sql = <<<SQL
            DELETE FROM tenants_rental_agreements 
            WHERE tenant_id = :tenant_id AND rental_agreement_id = :rental_agreement_id
            SQL;

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':tenant_id', $tenantId->toString());
            $stmt->bindValue(':rental_agreement_id', $rentalAgreementId->toString());
            $stmt->execute();
        } catch (PDOException) {
            throw new DatabaseException(DatabaseException::QUERY_FAILED);
        }
    }
}
