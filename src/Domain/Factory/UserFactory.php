<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\User;
use Domain\ValueObject\Email;
use Domain\ValueObject\PasswordHash;

final readonly class UserFactory
{
    public function __construct(
        private UlidFactory $ulidFactory
    ) {
    }

    public function create(string $name, Email $email, PasswordHash $passwordHash): User
    {
        $now = new DateTimeImmutable();
        return new User(
            id: $this->ulidFactory->userId(),
            email: $email,
            passwordHash: $passwordHash,
            name: $name,
            createdAt: $now,
            updatedAt: $now,
            lastLoginAt: $now,
            failedLoginAttempts: 0
        );
    }
}
