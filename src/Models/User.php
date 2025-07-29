<?php

namespace src\Models;

use src\Enums\Role;
use DateTime;

final readonly class User
{
    public function __construct(
        public int $id,
        public string $email,
        public string $passwordHash,
        public DateTime $createdAt,
        public ?DateTime $lastLoginAt,
        public int $failedLoginAttempts,
        public string $name,
        public Role $role,
    ) {}
}
