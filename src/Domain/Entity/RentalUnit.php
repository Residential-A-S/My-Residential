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
}
