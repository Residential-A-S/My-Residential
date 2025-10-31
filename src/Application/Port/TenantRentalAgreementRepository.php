<?php

namespace Application\Port;

use Domain\ValueObject\RentalAgreementId;
use Domain\ValueObject\TenantId;

/**
 *
 */
interface TenantRentalAgreementRepository
{
    /**
     * @param TenantId $tenantId
     * @param RentalAgreementId $rentalAgreementId
     *
     * @return void
     */
    public function addUserToRentalAgreement(TenantId $tenantId, RentalAgreementId $rentalAgreementId): void;

    /**
     * @param TenantId $tenantId
     * @param RentalAgreementId $rentalAgreementId
     *
     * @return void
     */
    public function removeTenantFromRentalAgreement(TenantId $tenantId, RentalAgreementId $rentalAgreementId): void;
}