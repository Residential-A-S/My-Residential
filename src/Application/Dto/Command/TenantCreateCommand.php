<?php

namespace Application\Dto\Command;

final readonly class TenantCreateCommand implements CommandInterface
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone
    ) {
    }
}
