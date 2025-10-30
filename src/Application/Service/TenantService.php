<?php

namespace Application\Service;

use Adapter\Persistence\Pdo\TenantRepository;
use Application\Exception\AuthenticationException;
use DateTimeImmutable;
use Domain\Exception\TenantException;
use Shared\Exception\ServerException;
use src\Entity\Tenant;

final readonly class TenantService
{
    public function __construct(
        private TenantRepository $tenantR,
        private AuthenticationService $authS,
    ) {
    }

    /**
     * @throws TenantException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function create(
        string $firstName,
        string $lastName,
        string $email,
        string $phone
    ): void {
        $this->authS->requireUser();
        $now = new DateTimeImmutable();
        $tenant = new Tenant(
            id: 0,
            firstName: $firstName,
            lastName: $lastName,
            email: $email,
            phone: $phone,
            createdAt: $now,
            updatedAt: $now,
        );
        $this->tenantR->create($tenant);
    }

    /**
     * @throws TenantException
     * @throws ServerException
     */
    public function update(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
        string $phone
    ): void {
        $tenant = $this->tenantR->requireId($id);

        $updatedTenant = new Tenant(
            id: $tenant->id,
            firstName: $firstName,
            lastName: $lastName,
            email: $email,
            phone: $phone,
            createdAt: $tenant->createdAt,
            updatedAt: new DateTimeImmutable(),
        );

        $this->tenantR->update($updatedTenant);
    }

    /**
     * @throws TenantException
     * @throws ServerException
     */
    public function delete(int $id): void
    {
        $tenant = $this->tenantR->requireId($id);
        $this->tenantR->delete($tenant->id);
    }
}
