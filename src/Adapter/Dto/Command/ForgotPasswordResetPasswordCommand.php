<?php

namespace Adapter\Dto\Command;

final readonly class ForgotPasswordResetPasswordCommand
{
    public function __construct(
        public string $token,
        public string $password,
        public string $repeatPassword,
    ) {
    }
}
