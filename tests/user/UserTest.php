<?php

namespace user;

use core\App;
use models\User;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

class UserTest extends TestCase
{
    private array $user_data_list;
    /**
     * @throws RandomException
     */
    public function setUp(): void
    {
        App::init();
        for ($i = 0; $i < 100; $i++) {
            $this->user_data_list[] = [
                "email" => "test" . uniqid() . "@propeteer.app",
                "password" => "Test1234!" . bin2hex(random_bytes(8)),
                "role"  => "admin"
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

    public function testCreate(): void
    {
        foreach ($this->user_data_list as $user_data) {
            $user = User::create(
                $user_data['email'],
                $user_data['password'],
                $user_data['role']
            );
            $this->assertIsNotArray($user, "User creation failed");
            $user = User::getByEmail($user_data['email']);
            $this->assertNotNull($user, "User not found");
            $this->assertEquals($user_data['email'], $user->email, "User email does not match"); //Test email
            $this->assertEquals($user_data['role'], $user->role, "User role does not match"); //Test role
            $this->assertTrue(
                password_verify(
                    $user_data['password'],
                    $user->password
                ),
                "User password does not match"
            ); //Test password
            $this->assertEquals($user->id, $user->id, "User ID does not match"); //Test ID
        }
    }
}
