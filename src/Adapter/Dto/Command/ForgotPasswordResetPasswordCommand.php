<?php

namespace Adapter\Dto\Command;

final readonly class ForgotPasswordResetPasswordCommand implements CommandInterface
{
    public function __construct(
        public string $token,
        public string $password
    ) {
    }
}
