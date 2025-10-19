<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\Address;
use Domain\ValueObject\OrganizationId;
use Domain\ValueObject\PropertyId;

final readonly class Property
{
    public function __construct(
        public PropertyId $id,
        public OrganizationId $organizationId,
        public Address $address,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }

    public function updateAddress(Address $newAddress): self
    {
        return new self(
            id: $this->id,
            organizationId: $this->organizationId,
            address: $newAddress,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function transferOwnership(OrganizationId $newOrganizationId): self
    {
        return new self(
            id: $this->id,
            organizationId: $newOrganizationId,
            address: $this->address,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }
}
