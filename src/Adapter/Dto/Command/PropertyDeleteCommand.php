<?php

namespace Adapter\Dto\Command;

final readonly class PropertyDeleteCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
