<?php

namespace Application\Port;

use Domain\Exception\UserException;
use Domain\Entity\User;

interface UserRepository
{
    /**
     * Find a user by their ID.
     * Throws a UserException if the user is not found.
     * @throws UserException
     */
    public function findById(int $id): User;

    /**
     * Find a user by their email.
     * Throws a UserException if the user is not found.
     * @throws UserException
     */
    public function findByEmail(string $email): User;

    /**
     * Find all users.
     * @return User[]
     */
    public function findAll(): array;

    /**
     * Create a new user or update an existing one.
     * @throws UserException
     */
    public function save(User $user): User;

    /**
     * Delete a user by their ID.
     * Throws a UserException if the user is not found.
     * @throws UserException
     */
    public function delete(int $id): void;

    public function existsByEmail(string $email): bool;

    public function existsById(int $id): bool;
}