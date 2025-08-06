<?php

namespace src\Repositories;

use PDO;
use src\Enums\Role;

final readonly class TenantRentalAgreementRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function addUserToRentalAgreement(int $tenantId, int $rentalAgreementId): void
    {
        $sql = <<<SQL
        INSERT INTO tenants_rental_agreements (tenant_id, rental_agreement_id) 
        VALUES (:tenant_id, :rental_agreement_id)
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tenant_id', $tenantId, PDO::PARAM_INT);
        $stmt->bindValue(':rental_agreement_id', $rentalAgreementId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function removeTenantFromRentalAgreement(int $tenantId, int $rentalAgreementId): void
    {
        $sql = <<<SQL
        DELETE FROM tenants_rental_agreements 
        WHERE tenant_id = :tenant_id AND rental_agreement_id = :rental_agreement_id
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tenant_id', $tenantId, PDO::PARAM_INT);
        $stmt->bindValue(':rental_agreement_id', $rentalAgreementId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
