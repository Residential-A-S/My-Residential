<?php

namespace Domain\ValueObject;

final readonly class RentalUnitId
{
    public function __construct(private string $ulid) {}
    public function toString(): string
    {
        return $this->ulid;
    }
}