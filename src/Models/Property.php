<?php

namespace src\Models;

use DateTimeImmutable;

final readonly class Property
{
    public function __construct(
        public int $id,
        public int $organizationId,
        public string $streetName,
        public string $streetNumber,
        public string $zipCode,
        public string $city,
        public string $country,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }
}
