<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\PropertyId;
use Domain\ValueObject\RentalUnitId;

final readonly class RentalUnit
{
    public function __construct(
        public RentalUnitId $id,
        public PropertyId $propertyId,
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }

    public function rename(string $newName): self
    {
        return new self(
            id: $this->id,
            propertyId: $this->propertyId,
            name: $newName,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }
}
