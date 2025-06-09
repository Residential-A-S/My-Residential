<?php

namespace core;

class Validate
{
    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    public static function password(string $password): bool
    {
        /*
        Password must be at least 8 characters long, contain at least one uppercase letter,
        one lowercase letter, one number, and one special character
        */
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password) === 1;
    }
    public static function role(string $role): bool
    {
        $validRoles = ['admin'];
        return in_array($role, $validRoles, true);
    }

    public static function string(string $string): bool
    {
        return !empty($string);
    }
}
