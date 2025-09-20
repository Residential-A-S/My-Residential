<?php

namespace src\Factories;

use src\Entity\Tenant;

final readonly class TenantFactory
{
    public function withId(Tenant $tenant, int $id): Tenant
    {
        return new Tenant(
            $id,
            $tenant->firstName,
            $tenant->lastName,
            $tenant->email,
            $tenant->phone,
            $tenant->createdAt,
            $tenant->updatedAt,
        );
    }
}
