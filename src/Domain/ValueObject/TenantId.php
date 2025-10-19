<?php

namespace Domain\ValueObject;

final readonly class TenantId
{
    public function __construct(private string $ulid) {}
    public function toString(): string
    {
        return $this->ulid;
    }
}