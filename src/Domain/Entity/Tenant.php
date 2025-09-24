<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\Email;
use Domain\ValueObject\TenantId;

final readonly class Tenant
{
    public function __construct(
        public TenantId $id,
        public string $firstName,
        public string $lastName,
        public Email $email,
        public string $phone,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }
}
