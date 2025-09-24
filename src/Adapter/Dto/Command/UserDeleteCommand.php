<?php

namespace Adapter\Dto\Command;

final readonly class UserDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
