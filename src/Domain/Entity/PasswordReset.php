<?php

namespace Domain\Entity;

use DateTimeImmutable;

final readonly class PasswordReset
{
    public function __construct(
        public int $userId,
        public string $token,
        public DateTimeImmutable $expiresAt,
        public DateTimeImmutable $createdAt
    ) {
    }
}
