<?php

namespace src\Factories;

use src\Models\Tenant;

final readonly class TenantFactory
{
    public function withId(Tenant $tenant, int $id): Tenant
    {
        return new Tenant(
            $id,
            $tenant->rentalAgreementId,
            $tenant->firstName,
            $tenant->lastName,
            $tenant->email,
            $tenant->phone,
            $tenant->createdAt,
            $tenant->updatedAt,
        );
    }
}