<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\Email;
use Domain\ValueObject\UserId;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public Email $email,
        public string $passwordHash,
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
        public ?DateTimeImmutable $lastLoginAt,
        public int $failedLoginAttempts,
    ) {
    }
}
