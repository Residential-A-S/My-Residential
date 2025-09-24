<?php

namespace Adapter\Dto\Command;

final readonly class TenantCreateCommand
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone
    ) {
    }
}
