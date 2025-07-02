<?php

namespace models;

use core\App;
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

    /**
     * Create a new user
     *
     * @param mixed $email
     * @param mixed $password
     * @param mixed $role
     *
     * @return array{false, string}|User
     */
    public static function create(mixed $email, mixed $password, mixed $role): array|User
    {
        if (!Validate::email($email) || !Validate::role($role) || !Validate::password($password)) {
            return [false, "invalid_input_format"]; // Invalid input format
        }
        // Check if email already exists
        if (self::emailExists($email)) {
            return [false, "email_exists"]; // Email already exists
        }

        $user_id = App::$db->insert("users", [
            "email"    => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role"     => $role
        ]);
        if (is_int($user_id)) {
            $user = self::getById($user_id);
            if (!$user) {
                return [false, "user_id_not_found"]; // User creation failed
            }
            return $user;
        }
        return [false, "user_creation_failed"]; // User creation failed
    }

    public function delete(): bool
    {
        $this->deleteToken();
        $result = App::$db->delete("users", [ "id" => $this->id ]);
        if ($result) {
            return true;
        }

        return false;
    }

    public static function emailExists(string $email): bool
    {
        $result = App::$db->selectSingle("users", "id", [ "email" => $email ]);
        if ($result) {
            return true;
        }

        return false;
    }
}
