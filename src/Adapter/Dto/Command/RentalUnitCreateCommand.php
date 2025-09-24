<?php

namespace Adapter\Dto\Command;

final readonly class RentalUnitCreateCommand implements CommandInterface
{
    public function __construct(
        public int $propertyId,
        public string $name
    ) {
    }
}
