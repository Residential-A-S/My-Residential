<?php

namespace Domain\ValueObject;

final readonly class PasswordHash
{
    public function __construct(private string $hash)
    {
        if (!password_get_info($hash)['algo']) {
            throw new \InvalidArgumentException('Invalid password hash.');
        }
    }

    public static function fromPlain(string $plain): self
    {
        return new self(password_hash($plain, PASSWORD_DEFAULT));
    }

    public function verify(string $plain): bool
    {
        return password_verify($plain, $this->hash);
    }

    public function __toString(): string
    {
        return $this->hash;
    }
}