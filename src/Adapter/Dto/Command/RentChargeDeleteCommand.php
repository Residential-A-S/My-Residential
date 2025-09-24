<?php

namespace Adapter\Dto\Command;

final readonly class RentChargeDeleteCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
