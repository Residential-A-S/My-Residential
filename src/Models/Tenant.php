<?php

namespace src\Models;

use DateTimeImmutable;

final readonly class Tenant
{
    public function __construct(
        public int $id,
        public int $rentalAgreementId,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {}
}