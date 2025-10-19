<?php

namespace Domain\ValueObject;

use Domain\Exception\PasswordResetException;

final readonly class PasswordResetToken
{
    /**
     * @throws PasswordResetException
     */
    public function __construct(public string $value)
    {
        if (empty($value) || !preg_match('/^[a-f0-9]{64}$/', $value)) {
            throw new PasswordResetException(PasswordResetException::TOKEN_NOT_SECURE);
        }
    }

    public function equals(PasswordResetToken $other): bool
    {
        return hash_equals($this->value, $other->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}