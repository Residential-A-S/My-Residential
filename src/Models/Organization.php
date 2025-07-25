<?php

namespace src\Models;

final class Organization
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description
    ) {}
}
