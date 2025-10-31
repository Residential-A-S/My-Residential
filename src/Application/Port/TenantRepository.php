<?php

namespace Application\Port;

use Domain\Entity\Tenant;
use Domain\ValueObject\TenantId;

/**
 *
 */
interface TenantRepository
{
    /**
     * @param TenantId $id
     *
     * @return Tenant
     */
    public function findById(TenantId $id): Tenant;

    /**
     * @return Tenant[]
     */
    public function findAll(): array;

    /**
     * @param Tenant $tenant
     *
     * @return void
     */
    public function save(Tenant $tenant): void;

    /**
     * @param TenantId $id
     *
     * @return void
     */
    public function delete(TenantId $id): void;
}