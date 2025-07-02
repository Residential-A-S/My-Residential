<?php

namespace core;

class Validate
{
    public static function email(mixed $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    public static function password(mixed $password): bool
    {
        /*
        Password must be at least 8 characters long and contain at least:
        - one lowercase letter
        - one uppercase letter
        - one digit
        - one special character from @#$%^&*()-_+={}[]|\;:"<>,./?
        */
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_+={}[\]\\|;:"<>,.\/\?]).{8,}$/';
        return preg_match($pattern, $password) === 1;
    }
    public static function role(mixed $role): bool
    {
        $validRoles = ['admin'];
        return in_array($role, $validRoles, true);
    }

    public static function string(mixed $string): bool
    {
        return !empty($string) && is_string($string) && trim($string) !== '';
    }
}
