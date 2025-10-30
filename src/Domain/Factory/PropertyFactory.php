<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\Property;
use Domain\ValueObject\Address;
use Domain\ValueObject\OrganizationId;

final readonly class PropertyFactory
{
    public function __construct(
        private UlidFactory $ulidFactory
    ) {
    }

    public function create(Address $address, OrganizationId $organizationId): Property
    {
        $now = new DateTimeImmutable();
        return new Property(
            id: $this->ulidFactory->propertyId(),
            organizationId: $organizationId,
            address: $address,
            createdAt: $now,
            updatedAt: $now
        );
    }
}
