<?php

namespace Adapter\Dto\Command;

final readonly class UserLoginCommand
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
