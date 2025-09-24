<?php

namespace Adapter\Dto\Command;

final readonly class RentalUnitDeleteCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
