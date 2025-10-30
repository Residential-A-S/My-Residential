<?php

namespace Application\Port;

use Domain\Entity\PasswordReset;
use Domain\ValueObject\PasswordResetToken;

/**
 *
 */
interface PasswordResetRepository
{
    /**
     * Saves a new password reset token.
     * @param PasswordReset $passwordReset
     * @return void
     */
    public function savePasswordResetToken(PasswordReset $passwordReset): void;


    /**
     * Find a password reset by its token.
     * Throws a PasswordResetException if the token is not found.
     *
     * @param PasswordResetToken $token
     *
     * @return PasswordReset
     */
    public function findByToken(PasswordResetToken $token): PasswordReset;


    /**
     * Delete a password reset by its token.
     * Throws a PasswordResetException if the token is not found.
     * @param PasswordResetToken $token
     * @return void
     */
    public function deleteByToken(PasswordResetToken $token): void;
}