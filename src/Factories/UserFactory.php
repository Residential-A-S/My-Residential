<?php

namespace src\Factories;

use src\Models\User;

final readonly class UserFactory
{
    public function withUpdatedPassword(User $user, string $hashedPassword): User
    {
        return new User(
            $user->id,
            $user->email,
            $hashedPassword,
            $user->createdAt,
            $user->lastLoginAt,
            $user->failedLoginAttempts,
            $user->name,
            $user->role
        );
    }

    public function withUpdatedName(User $user, string $name): User
    {
        return new User(
            $user->id,
            $user->email,
            $user->passwordHash,
            $user->createdAt,
            $user->lastLoginAt,
            $user->failedLoginAttempts,
            $name,
            $user->role
        );
    }

    public function withUpdatedEmail(User $user, string $email): User
    {
        return new User(
            $user->id,
            $email,
            $user->passwordHash,
            $user->createdAt,
            $user->lastLoginAt,
            $user->failedLoginAttempts,
            $user->name,
            $user->role
        );
    }
}