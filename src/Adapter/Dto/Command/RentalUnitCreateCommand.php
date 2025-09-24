<?php

namespace Adapter\Dto\Command;

final readonly class RentalUnitCreateCommand
{
    public function __construct(
        public int $propertyId,
        public string $name
    ) {
    }
}
