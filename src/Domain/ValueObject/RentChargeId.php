<?php

namespace Domain\ValueObject;

final readonly class RentChargeId
{
    public function __construct(private string $ulid) {}
    public function toString(): string
    {
        return $this->ulid;
    }
}