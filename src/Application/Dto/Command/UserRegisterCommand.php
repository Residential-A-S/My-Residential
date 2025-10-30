<?php

namespace Application\Dto\Command;

final readonly class UserRegisterCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $password,
        public string $name,
    ) {
    }
}
