<?php

namespace src\Models;

use DateTimeImmutable;

final readonly class Organization
{
    public function __construct(
        public int $id,
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {}
}
