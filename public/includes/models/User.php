<?php

namespace models;

use core\Validate;
use traits\UserAuthentication;
use traits\UserGetters;
use traits\UserUpdates;

class User
{
    use UserAuthentication;
    use UserGetters;
    use UserUpdates;

    public int $id {
        get => $this->id;
        set => $this->id = $value;
    }
    public string $email {
        get {
            return $this->email;
        }
    }
    public string $password {
        get {
            return $this->password;
        }
    }
    public string $role {
        get {
            return $this->role;
        }
    }
    public array $organizations {
        get {
            return $this->organizations;
        }
    }

    public function __construct(int $id, string $email, string $password, string $role)
    {
        $this->id       = $id;
        $this->email    = $email;
        $this->password = $password;
        $this->role     = $role;
    }

    public static function create(string $email, string $password, string $role): bool
    {
        if (!Validate::email($email) || !Validate::role($role) || !Validate::password($password)) {
            return false; // Invalid email format
        }
        // Check if email already exists
        if (self::emailExists($email)) {
            return false; // Email already exists
        }

        $user_id = db()->insert("user", [
            "email"    => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role"     => $role
        ]);
        if (is_int($user_id)) {
            return true;
        }

        return false;
    }

    public function delete(): bool
    {
        $result = db()->delete("user", [ "id" => $this->id ]);
        if ($result) {
            return true;
        }

        return false;
    }

    public static function emailExists(string $email): bool
    {
        $result = db()->selectSingle("user", "id", [ "email" => $email ]);
        if ($result) {
            return true;
        }

        return false;
    }
}
