<?php

namespace traits;

use core\App;
use models\User;

trait UserUpdates
{
    public function updateEmail(string $new_email): bool
    {
        if (User::emailExists($new_email)) {
            return false; // Email already exists
        }

        $result = App::$db->update("user", [ "email" => $new_email ], [ "ID" => $this->id ]);
        return $result > 0;
    }

    public function updatePassword(string $new_password): bool
    {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $result = App::$db->update("user", [ "password" => $hashed_password ], [ "ID" => $this->id ]);
        return $result > 0;
    }

    public function updateRole(string $new_role): bool
    {
        $valid_roles = [ 'user', 'admin' ]; // Define valid roles
        if (!in_array($new_role, $valid_roles)) {
            return false; // Invalid role
        }

        $result = App::$db->update("user", [ "role" => $new_role ], [ "ID" => $this->id ]);
        return $result > 0;
    }
}
