<?php

namespace Adapter\Dto\Command;

final readonly class UserRegisterCommand
{
    public function __construct(
        public int $id,
        public string $email,
        public string $password,
        public string $name,
    ) {
    }
}
