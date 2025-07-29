<?php

namespace src\Models;

use DateTime;

final readonly class Organization
{
    public function __construct(
        public int $id,
        public string $name,
        public DateTime $createdAt,
    ) {}
}
