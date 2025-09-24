<?php

namespace Adapter\Dto\Command;

final readonly class ChangePasswordCommand
{
    public function __construct(
        public string $password,
        public string $repeatPassword,
    ) {
    }
}
