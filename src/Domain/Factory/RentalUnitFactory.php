<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\RentalUnit;
use Domain\ValueObject\PropertyId;

final readonly class RentalUnitFactory
{
    public function __construct(
        private UlidFactory $ulidFactory
    ) {
    }

    public function create(PropertyId $propertyId, string $name): RentalUnit
    {
        $now = new DateTimeImmutable();
        return new RentalUnit(
            id: $this->ulidFactory->rentalUnitId(),
            propertyId: $propertyId,
            name: $name,
            createdAt: $now,
            updatedAt: $now
        );
    }
}
