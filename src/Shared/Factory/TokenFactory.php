<?php

namespace Shared\Factory;

use Domain\ValueObject\PasswordResetToken;
use Shared\Exception\TokenGenerationException;
use Shared\Infrastructure\TokenGenerator;

final readonly class TokenFactory
{
    public function __construct(private TokenGenerator $tokenGenerator)
    {
    }

    /**
     * @throws TokenGenerationException
     */
    public function resetPasswordToken(): PasswordResetToken
    {
        return new PasswordResetToken($this->tokenGenerator->generate());
    }
}