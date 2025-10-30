<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\Tenant;
use Domain\ValueObject\Email;
use Domain\ValueObject\Phone;

final readonly class TenantFactory
{
    public function __construct(
        private UlidFactory $ulidFactory
    ) {
    }

    public function create(string $firstName, string $lastName, Email $email, Phone $phone): Tenant
    {
        $now = new DateTimeImmutable();
        return new Tenant(
            id: $this->ulidFactory->tenantId(),
            firstName: $firstName,
            lastName: $lastName,
            email: $email,
            phone: $phone,
            createdAt: $now,
            updatedAt: $now
        );
    }
}
