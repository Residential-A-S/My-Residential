<?php

namespace Adapter\Dto\Command;

final readonly class ForgotPasswordSendVerificationCommand implements CommandInterface
{
    public function __construct(
        public string $email
    ) {
    }
}
