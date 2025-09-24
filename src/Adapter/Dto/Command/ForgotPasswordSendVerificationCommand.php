<?php

namespace Adapter\Dto\Command;

final readonly class ForgotPasswordSendVerificationCommand
{
    public function __construct(
        public string $email
    ) {
    }
}
