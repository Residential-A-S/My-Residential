<?php

namespace src\Enums;

enum Role
{
    case SuperAdmin;
    case Admin;
    case Tenant;

    public function to(): string {
        return match($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Tenant => 'Tenant',
        };
    }

    public static function from(string $role): self {
        return match($role) {
            'Super Admin' => self::SuperAdmin,
            'Admin' => self::Admin,
            'Tenant' => self::Tenant,
            default => throw new \InvalidArgumentException("Invalid role: $role"),
        };
    }
}
