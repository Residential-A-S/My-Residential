<?php

namespace Domain\ValueObject;

use DateTimeImmutable;

final readonly class PasswordReset
{
    public function __construct(
        public UserId $userId,
        public string $token,
        public DateTimeImmutable $expiresAt,
        public DateTimeImmutable $createdAt
    ) {
    }
}
