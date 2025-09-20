<?php

namespace Tests\Repositories;

use src\Types\Role;
use src\Entity\User;
use Tests\BaseTest;

class UserRepositoryTest extends BaseTest {
    private array $userData;
    public function setUp(): void {
        parent::setUp();
        // Mock user data for testing
        $this->userData = [
            "email" => "julius.jensen02@gmail.com",
            "password" => "password123",
            "name" => "Julius Jensen",
            "role" => Role::Admin
        ];
    }

    public function testFindAll() {

    }

    public function testUpdate() {

    }

    public function testExistsById() {

    }

    public function testDelete() {

    }

    public function testCreate() {
        $user = $this->userRepo->create(
            $this->userData['email'],
            $this->userData['password'],
            $this->userData['name'],
            $this->userData['role']
        );

        $this->assertInstanceOf(User::class, $user, "User should be an instance of User class");
        $this->assertEquals($this->userData['email'], $user->email, "User email should match the expected email");
        $this->assertEquals($this->userData['name'], $user->name, "User name should match the expected name");
        $this->assertEquals($this->userData['role'], $user->role, "User role should match the expected role");
        $this->assertIsInt($user->id, "User ID should be an integer");
    }

    public function testFindById() {

    }

    public function testHydrate() {

    }

    public function testExistsByEmail() {

    }

    public function testFindByEmail() {

    }
}
