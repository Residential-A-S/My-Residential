<?php

namespace Domain\ValueObject;

final readonly class Address
{
    public function __construct(
        public string $streetName,
        public string $streetNumber,
        public string $zipCode,
        public string $city,
        public string $country,
    ) {
    }

    public function __toString(): string
    {
        return $this->streetName . ' ' . $this->streetNumber . ', ' . $this->zipCode . ' ' . $this->city . ', ' . $this->country;
    }
}