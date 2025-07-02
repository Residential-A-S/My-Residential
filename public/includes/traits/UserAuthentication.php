<?php

namespace traits;

use core\App;
use core\Validate;
use models\User;

trait UserAuthentication
{
    public static function register(array $data): bool
    {
        if (!self::areRegisterCredentialsValid($data)) {
            App::$response_message_code = "invalid_register_credentials";
            return false;
        }
        $user = self::create($data['email'], $data['password'], "admin");

        if ($user instanceof User) {
            App::$response_message_code =  "user_created";
            return true;
        }

        App::$response_message_code = "user_creation_failed";
        return false;
    }

    public static function login(array $data): bool
    {
        if (! isset($data['email']) || ! isset($data['password'])) {
            App::$response_message_code = "missing_login_credentials";
            return false;
        }
        $email    = $data['email'];
        $password = $data['password'];
        $user     = self::getByEmail($email);
        if (! isset($user)) {
            App::$response_message_code = "invalid_login_credentials";
            return false;
        }

        if (password_verify($password, $user->password)) {
            $_SESSION['token'] = $user->generateToken();
            App::$response_message_code = "user_logged_in";
            return true;
        }

        App::$response_message_code = "invalid_login_credentials";
        return false;
    }

    public static function logout(): bool
    {
        if (isset($_SESSION['token'])) {
            $user = User::getByToken($_SESSION['token']);
            if (! $user) {
                App::$response_message_code = "logout_user_not_logged_in";
                return false;
            }

            $user->deleteToken();
            session_unset();
            session_destroy();
            App::$response_message_code = "user_logged_out";
            return true;
        }

        App::$response_message_code = "logout_user_not_logged_in";
        return false;
    }

    private static function areRegisterCredentialsValid(array $data): bool
    {
        return Validate::email($data['email']) && Validate::password($data['password']) &&
               $data['password'] === $data['repeat_password'];
    }

    private function generateToken(): string
    {
        $token = uniqid($this->email, true);
        if ($this->getToken()) {
            $this->updateToken($token);
        } else {
            $this->insertToken($token);
        }

        return $token;
    }

    private function getToken(): string|false
    {
        $token = App::$db->selectSingle("tokens", "token", ["user_id" => $this->id]);
        if ($token) {
            return $token['token'];
        }

        return false;
    }

    private function updateToken(string $token): void
    {
        App::$db->update("tokens", ["token" => $token], ["user_id" => $this->id]);
    }

    private function insertToken(string $token): void
    {
        App::$db->insert("tokens", ["token" => $token, "user_id" => $this->id]);
    }

    private function deleteToken(): void
    {
        App::$db->delete("tokens", ["user_id" => $this->id]);
    }
}
