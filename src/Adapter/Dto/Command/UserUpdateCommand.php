<?php

namespace Adapter\Dto\Command;

final readonly class UserUpdateCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $name,
    ) {
    }
}
