<?php

namespace Domain\ValueObject;

use Domain\Shared\Id;

final readonly class UserId implements Id
{
    public function __construct(private string $ulid) {}
    public function toString(): string
    {
        return $this->ulid;
    }
}