<?php

namespace Adapter\Dto\Command;

final readonly class RentChargeDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
