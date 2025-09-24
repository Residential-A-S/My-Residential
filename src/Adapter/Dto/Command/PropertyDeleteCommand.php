<?php

namespace Adapter\Dto\Command;

final readonly class PropertyDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
