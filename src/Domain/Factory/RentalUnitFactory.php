<?php

namespace Domain\Factory;

use Domain\Entity\RentalUnit;

final readonly class RentalUnitFactory
{
    public function withId(RentalUnit $rentalUnit, int $id): RentalUnit
    {
        return new RentalUnit(
            $id,
            $rentalUnit->propertyId,
            $rentalUnit->name,
            $rentalUnit->status,
            $rentalUnit->createdAt,
            $rentalUnit->updatedAt
        );
    }
}
