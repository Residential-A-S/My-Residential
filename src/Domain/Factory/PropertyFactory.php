<?php

namespace Domain\Factory;

use Domain\Entity\Property;

final readonly class PropertyFactory
{
    public function withId(Property $property, int $id): Property
    {
        return new Property(
            $id,
            $property->organizationId,
            $property->streetName,
            $property->streetNumber,
            $property->zipCode,
            $property->city,
            $property->country,
            $property->createdAt,
            $property->updatedAt
        );
    }
}
