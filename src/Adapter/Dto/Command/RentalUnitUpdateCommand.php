<?php

namespace Adapter\Dto\Command;

final readonly class RentalUnitUpdateCommand
{
    public function __construct(
        public int $id,
        public int $propertyId,
        public string $name
    ) {
    }
}
