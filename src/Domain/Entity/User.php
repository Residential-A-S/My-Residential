<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\Email;
use Domain\ValueObject\PasswordHash;
use Domain\ValueObject\UserId;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public Email $email,
        public PasswordHash $passwordHash,
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
        public ?DateTimeImmutable $lastLoginAt,
        public int $failedLoginAttempts,
    ) {
    }

    public function resetFailedLogins(): self
    {
        return new self(
            id: $this->id,
            email: $this->email,
            passwordHash: $this->passwordHash,
            name: $this->name,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
            lastLoginAt: new DateTimeImmutable(),
            failedLoginAttempts: 0,
        );
    }

    public function incrementFailedLogin(): self
    {
        return new self(
            id: $this->id,
            email: $this->email,
            passwordHash: $this->passwordHash,
            name: $this->name,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
            lastLoginAt: $this->lastLoginAt,
            failedLoginAttempts: $this->failedLoginAttempts + 1,
        );
    }

    public function rename(string $newName): self
    {
        return new self(
            id: $this->id,
            email: $this->email,
            passwordHash: $this->passwordHash,
            name: $newName,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
            lastLoginAt: $this->lastLoginAt,
            failedLoginAttempts: $this->failedLoginAttempts,
        );
    }

    public function changePassword(PasswordHash $newPasswordHash): self
    {
        return new self(
            id: $this->id,
            email: $this->email,
            passwordHash: $newPasswordHash,
            name: $this->name,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
            lastLoginAt: $this->lastLoginAt,
            failedLoginAttempts: $this->failedLoginAttempts,
        );
    }

    public function changeEmail(Email $newEmail): self
    {
        return new self(
            id: $this->id,
            email: $newEmail,
            passwordHash: $this->passwordHash,
            name: $this->name,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
            lastLoginAt: $this->lastLoginAt,
            failedLoginAttempts: $this->failedLoginAttempts,
        );
    }

    public function isLocked(): bool
    {
        return $this->failedLoginAttempts >= 5;
    }
}
