<?php

namespace traits;

use models\User;

trait UserAuthentication
{
    public static function register(array $data): bool
    {
        if (self::checkRegisterCredentials($data)) {
            REQUEST->error("invalid_register_credentials");

            return false;
        }
        $user = self::create($data['email'], $data['password'], "admin");

        if ($user) {
            REQUEST->success("user_created");

            return true;
        }
        REQUEST->error("user_creation_failed");

        return false;
    }

    public static function login(array $data): bool
    {
        if (! isset($data['email']) || ! isset($data['password'])) {
            REQUEST->error("missing_login_credentials");

            return false;
        }
        $email    = $data['email'];
        $password = $data['password'];
        $user     = self::getUserByEmail($email);
        if (! isset($user)) {
            REQUEST->error("invalid_login_credentials");

            return false;
        }
        if (password_verify($password, $user->password)) {
            $_SESSION['token'] = $user->generateToken();
            REQUEST->success("user_logged_in");

            return true;
        }

        REQUEST->error("invalid_login_credentials");

        return false;
    }

    public static function logout(): bool
    {
        if (isset($_SESSION['token'])) {
            $user = User::getUserByToken($_SESSION['token']);
            if (! $user) {
                REQUEST->error("logout_user_not_logged_in");

                return false;
            }
            $user->deleteToken();
            session_destroy();

            REQUEST->success("user_logged_out");

            return true;
        }

        REQUEST->error("logout_error");

        return false;
    }

    private static function checkRegisterCredentials(array $data): bool
    {
        return self::isEmailValid($data['email']) &&
               self::doesPasswordsMatch($data['password'], $data['repeatPassword']);
    }

    private static function isEmailValid(string $email): bool
    {
        return ! empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private static function doesPasswordsMatch(string $password, string $repeatPassword): bool
    {
        return ! empty($password) &&
               ! empty($repeatPassword) &&
               $password === $repeatPassword &&
               strlen($password) >= 8;
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
        $token = db()->selectSingle("token", "token", ["user_id" => $this->id]);
        if ($token) {
            return $token['token'];
        }

        return false;
    }

    private function updateToken(string $token): void
    {
        db()->update("token", ["token" => $token], ["user_id" => $this->id]);
    }

    private function insertToken(string $token): void
    {
        db()->insert("token", ["token" => $token, "user_id" => $this->id]);
    }

    private function deleteToken(): void
    {
        db()->delete("token", ["user_id" => $this->id]);
    }
}
