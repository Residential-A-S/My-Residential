<?php

namespace Application\Dto\Command;

final readonly class RentalUnitUpdateCommand implements CommandInterface
{
    public function __construct(
        public int $id,
        public int $propertyId,
        public string $name
    ) {
    }
}
