<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\UserId;

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
