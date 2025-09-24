<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\User;

final readonly class UserFactory
{
    public function withId(User $user, int $id): User
    {
        return new User(
            $id,
            $user->email,
            $user->passwordHash,
            $user->name,
            $user->createdAt,
            $user->updatedAt,
            $user->lastLoginAt,
            $user->failedLoginAttempts
        );
    }

    public function withUpdatedPassword(User $user, string $passwordHash): User
    {
        return new User(
            $user->id,
            $user->email,
            $passwordHash,
            $user->name,
            $user->createdAt,
            $user->updatedAt,
            $user->lastLoginAt,
            $user->failedLoginAttempts
        );
    }

    public function withUpdatedInfo(User $user, ?string $name = null, ?string $email = null): User
    {
        $name = $name ?? $user->name;
        $email = $email ?? $user->email;
        return new User(
            $user->id,
            $email,
            $user->passwordHash,
            $name,
            $user->createdAt,
            new DateTimeImmutable(),
            $user->lastLoginAt,
            $user->failedLoginAttempts
        );
    }
}
