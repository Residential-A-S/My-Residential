<?php

namespace Domain\ValueObject;

final readonly class RentalAgreementId
{
    public function __construct(private string $ulid) {}
    public function toString(): string
    {
        return $this->ulid;
    }
}