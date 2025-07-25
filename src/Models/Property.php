<?php

namespace src\Models;

final class Property
{
    public function __construct(
        public int $id,
        public string $country,
        public string $postalCode,
        public string $city,
        public string $address,
    ) {}
}
