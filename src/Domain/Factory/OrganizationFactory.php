<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\Organization;

final readonly class OrganizationFactory
{
    public function __construct(
        private UlidFactory $ulidFactory
    ) {
    }

    public function create(string $name): Organization
    {
        $now = new DateTimeImmutable();
        return new Organization(
            id: $this->ulidFactory->organizationId(),
            name: $name,
            createdAt: $now,
            updatedAt: $now
        );
    }
}
