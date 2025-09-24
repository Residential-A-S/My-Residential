<?php

namespace Adapter\Dto\Command;

final readonly class RentalUnitDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
