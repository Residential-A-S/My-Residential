<?php

namespace traits;

use core\App;
use models\User;

trait UserGetters
{
    /**
     * @param string $role
     *
     * @return User[]|null
     */
    public static function getAllByRole(string $role): array|null
    {
        $userIDs = App::$db->selectAll("users", "id", [ "role" => $role ]);
        $users   = [];
        foreach ($userIDs as $userID) {
            $users[] = self::getById($userID['id']);
        }
        if (count($users) > 0) {
            return null;
        }

        return $users;
    }

    public static function getById(int $user_id): User|null
    {
        $user_row = App::$db->selectSingle("users", "*", [ "id" => $user_id ]);

        return new User($user_row["id"], $user_row["email"], $user_row["password"], $user_row["role"]);
    }


    public static function getByEmail(string $email): User|null
    {
        $user = App::$db->selectSingle("users", "id", [ "email" => $email ]);
        if (isset($user["id"])) {
            return self::getById($user['id']);
        }

        return null;
    }


    public static function getByToken(string $token): User|null
    {
        $row = App::$db->selectSingle("tokens", "*", [ "token" => $token ]);
        // Check if the token exists and is not expired
        // Tokens are valid for 1 hour
        if ($row && $row["created_at"] > date("Y-m-d H:i:s", strtotime("-1 hour"))) {
            return self::getById($row['user_id']);
        }
        else if ($row) {
            // If the token is expired, delete it
            App::$db->delete("tokens", [ "token" => $token ]);
        }

        return null;
    }
}
