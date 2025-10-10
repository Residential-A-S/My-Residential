<?php

namespace Application\Port;

use Domain\Exception\PasswordResetException;
use Domain\ValueObject\PasswordReset;

interface PasswordResetRepository
{
    /**
     * Insert a new password reset token.
     * @throws PasswordResetException
     */
    public function insertPasswordResetToken(PasswordReset $passwordReset): PasswordReset;


    /**
     * Find a password reset by its token.
     * Throws a PasswordResetException if the token is not found.
     * @throws PasswordResetException
     */
    public function findByToken(string $token): PasswordReset;


    /**
     * Delete a password reset by its token.
     * Throws a PasswordResetException if the token is not found.
     * @throws PasswordResetException
     */
    public function deleteByToken(string $token): PasswordReset;
}