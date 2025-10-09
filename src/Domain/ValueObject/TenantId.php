<?php

namespace Domain\ValueObject;

use Domain\Shared\Id;

final readonly class TenantId implements Id
{
    public function __construct(private string $ulid) {}
    public function toString(): string
    {
        return $this->ulid;
    }
}