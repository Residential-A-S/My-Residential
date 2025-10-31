<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\PropertyId;
use Domain\ValueObject\RentalUnitId;

/**
 *
 */
final readonly class RentalUnit
{
    /**
     * @param RentalUnitId $id
     * @param PropertyId $propertyId
     * @param string $name
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     */
    public function __construct(
        public RentalUnitId $id,
        public PropertyId $propertyId,
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }

    /**
     * @param string $newName
     *
     * @return self
     */
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
