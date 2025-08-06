<?php

namespace src\Models;

use DateTimeImmutable;

final readonly class RentalUnit
{
    public function __construct(
        public int $id,
        public int $propertyId,
        public string $name,
        public string $status,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }
}
