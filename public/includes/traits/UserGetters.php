<?php

namespace traits;

use models\User;

trait UserGetters
{
    /**
     * @param string $role
     *
     * @return User[]|null
     */
    public static function getAllUsersByRole(string $role): array|null
    {
        $userIDs = db()->selectAll("user", "ID", [ "role" => $role ]);
        $users   = [];
        foreach ($userIDs as $userID) {
            $users[] = self::getUserByID($userID['ID']);
        }
        if (count($users) > 0) {
            return null;
        }

        return $users;
    }

    public static function getUserByID(int $user_id): User|null
    {
        $user_row = db()->selectSingle("user", "*", [ "ID" => $user_id ]);

        return new User($user_row["id"], $user_row["email"], $user_row["password"], $user_row["role"]);
    }


    public static function getUserByEmail(string $email): User|null
    {
        $user = db()->selectSingle("user", "ID", [ "email" => $email ]);
        if (isset($user["ID"])) {
            return self::getUserByID($user['ID']);
        }

        return null;
    }


    public static function getUserByToken(string $token): User|null
    {
        $row = db()->selectSingle("token", "*", [ "token" => $token ]);
        if ($row) {
            return self::getUserByID($row['userID']);
        }

        return null;
    }
}
