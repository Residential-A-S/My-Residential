<?php

namespace Application\Port;

use Domain\Entity\User;

interface UserRepository
{
    /**
     * Find a user by their ID.
     */
    public function findById(int $id): ?User;

    /**
     * Find a user by their email.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find all users.
     * @return User[]
     */
    public function findAll(): array;

    /**
     * Create a new user or update an existing one.
     */
    public function save(User $user): User;

    /**
     * Delete a user by their ID.
     */
    public function delete(int $id): void;

    public function existsByEmail(string $email): bool;

    public function existsById(int $id): bool;
}