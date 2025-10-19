<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\PasswordResetId;
use Domain\ValueObject\PasswordResetToken;
use Domain\ValueObject\UserId;

final readonly class PasswordReset
{
    public function __construct(
        public PasswordResetId $id,
        public UserId $userId,
        public PasswordResetToken $token,
        public DateTimeImmutable $expiresAt,
        public DateTimeImmutable $createdAt
    ) {
    }

    public function isExpired(): bool
    {
        return new DateTimeImmutable() > $this->expiresAt;
    }
}
