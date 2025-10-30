<?php

namespace Application\Dto\Command;

final readonly class UserForgotPasswordCommand implements CommandInterface
{
    public function __construct(
        public string $email
    ) {
    }
}
