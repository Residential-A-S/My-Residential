<?php

namespace Domain\Factory;

use Domain\Exception\PasswordResetException;
use Domain\Exception\TokenGenerationException;
use Domain\Service\TokenGenerator;
use Domain\ValueObject\PasswordResetToken;

final readonly class TokenFactory
{
    public function __construct(private TokenGenerator $tokenGenerator)
    {
    }

    /**
     * @throws TokenGenerationException|PasswordResetException
     */
    public function resetPasswordToken(): PasswordResetToken
    {
        return new PasswordResetToken($this->tokenGenerator->generate());
    }
}