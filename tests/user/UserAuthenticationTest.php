<?php

namespace user;

use Core\App;
use Core\Validate;
use src\Models\User;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

class UserAuthenticationTest extends TestCase
{
    private array $user_data_list;
    /**
     * @throws RandomException
     */
    public function setUp(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $password = "Test1234!" . bin2hex(random_bytes(8));
            $this->user_data_list[] = [
                "email" => "test" . uniqid() . "@propeteer.app",
                "password" => $password,
                "repeat_password" => $password
            ];
        }
    }

    public function tearDown(): void
    {
        foreach ($this->user_data_list as $user_data) {
            $user = User::getByEmail($user_data['email']);
            $user?->delete();
        }
    }

    public function testUserAuthentication(): void
    {
        foreach ($this->user_data_list as $user_data) {
            $this->testRegister($user_data);
            $this->testLogin($user_data);
            $this->testLogout($user_data);
        }
    }

    private function testRegister(array $user_data): void
    {
        App::init();
        $this->assertTrue(Validate::email($user_data['email']));
        $this->assertTrue(Validate::password($user_data['password']));
        $this->assertTrue($user_data['password'] === $user_data['repeat_password']);
        $success = User::register($user_data);
        $this->assertTrue($success, "User registration failed" .
            " for email: " . $user_data['email'] .
            " for password: " . $user_data['password'] .
            " with message: " . App::$response_message_code);
        $user = User::getByEmail($user_data['email']);
        $this->assertNotNull($user, "User not found");
    }

    private function testLogin(array $user_data): void
    {
        App::init();
        $this->assertTrue(Validate::email($user_data['email']));
        $this->assertTrue(Validate::password($user_data['password']));
        $success = User::login($user_data);
        $this->assertTrue($success, "User login failed" .
            " for email: " . $user_data['email'] .
            " for password: " . $user_data['password'] .
            " with message: " . App::$response_message_code);
        $user = User::getByEmail($user_data['email']);
        $this->assertNotNull($user, "User not found after login");

        App::init();
        $this->assertNotNull($_SESSION['token'], "Session token not set after login");
        $this->assertTrue(App::$user instanceof User, "App user instance is not a User");
        $this->assertTrue(App::isLoggedIn(), "App user is not logged in");
    }

    private function testLogout(array $user_data): void
    {
        App::init();
        $success = User::logout();
        $this->assertTrue($success, "User logout failed" .
            " for email: " . $user_data['email'] .
            " with message: " . App::$response_message_code);
        $this->assertNull($_SESSION['token'], "Session token not cleared after logout");
    }
}
