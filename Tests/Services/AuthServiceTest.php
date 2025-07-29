<?php

namespace Tests\Services;

use Tests\BaseTest;
use src\Models\User;

class AuthServiceTest extends BaseTest
{
    private array $userData;

    public function setUp(): void {
        parent::setUp();
        // Mock user data for testing
        $this->userData = [
            "email" => "julius.jensen02@gmail.com",
            "password" => "password123",
            "name" => "Julius Jensen"
        ];
    }

    public function testRegister()
    {
        $user = $this->authService->register(
            $this->userData['email'],
            $this->userData['password'],
            $this->userData['name']
        );
        $this->assertInstanceOf(User::class, $user);
    }

    public function testIsAuthenticated() {

    }

    public function testGetCurrentUser() {

    }

    public function testRequireUser() {

    }

    public function testLogin() {

    }
}
