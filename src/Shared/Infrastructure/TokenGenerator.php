<?php

namespace Shared\Infrastructure;

use Random\RandomException;
use Shared\Exception\TokenGenerationException;

final readonly class TokenGenerator
{
    /**
     * Generates a secure random token with the specified length.
     * @throws TokenGenerationException
     */
    public function generate(int $length = 64): string
    {
        try{
            return bin2hex(random_bytes($length / 2));
        } catch (RandomException) {
            throw new TokenGenerationException(TokenGenerationException::COULD_NOT_GENERATE_SECURE_TOKEN);
        }
    }
}