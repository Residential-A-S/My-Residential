<?php

namespace Application\Dto\Command;

final readonly class UserLoginCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
