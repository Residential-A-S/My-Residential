<?php

namespace Adapter\Dto\Command;

final readonly class UserForgotPasswordCommand
{
    public function __construct(
        public string $email
    ) {
    }
}
