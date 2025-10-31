<?php

namespace Application\Port;

use Domain\Entity\User;
use Domain\ValueObject\Email;
use Domain\ValueObject\UserId;

/**
 *
 */
interface UserRepository
{

    /**
     * @param UserId $id
     *
     * @return User
     */
    public function findById(UserId $id): User;

    /**
     * @param Email $email
     *
     * @return User
     */
    public function findByEmail(Email $email): User;

    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param User $user
     *
     * @return void
     */
    public function save(User $user): void;

    /**
     * @param UserId $id
     *
     * @return void
     */
    public function delete(UserId $id): void;

    /**
     * @param Email $email
     *
     * @return bool
     */
    public function existsByEmail(Email $email): bool;

    /**
     * @param UserId $id
     *
     * @return bool
     */
    public function existsById(UserId $id): bool;
}