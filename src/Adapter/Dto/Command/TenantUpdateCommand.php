<?php

namespace Adapter\Dto\Command;

final readonly class TenantUpdateCommand implements CommandInterface
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone
    ) {
    }
}
