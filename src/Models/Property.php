<?php

namespace src\Models;

use DateTime;

final readonly class Property
{
    public function __construct(
        public int $id,
        public string $country,
        public string $zip,
        public string $city,
        public string $address,
        public DateTime $createdAt,
    ) {}
}
