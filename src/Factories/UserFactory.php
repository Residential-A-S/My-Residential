<?php

namespace src\Factories;

use src\Models\User;

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
}
