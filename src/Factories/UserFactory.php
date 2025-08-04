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
}