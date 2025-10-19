<?php

namespace Domain\ValueObject;

final readonly class PasswordResetId
{
    public function __construct(private string $ulid) {}
    public function toString(): string
    {
        return $this->ulid;
    }
}