<?php

namespace Domain\Entity;

use DateTimeImmutable;

final readonly class User
{
    public function __construct(
        public ?int $id,
        public string $email,
        public string $passwordHash,
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
        public ?DateTimeImmutable $lastLoginAt,
        public int $failedLoginAttempts,
    ) {
    }
}
