<?php

namespace Adapter\Dto\Command;

final readonly class UserDeleteCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
