<?php

namespace Tests\Controllers;

use src\Controllers\Api\UserController;
use src\Core\Request;
use Tests\BaseTest;

class UserControllerTest extends BaseTest
{
    private array $userData;
    public function setUp(): void {
        parent::setUp();
        // Mock user data for testing
        $this->userData = [
            "email" => "julius.jensen02@gmail.com",
            "password" => "Password123!",
            "name" => "Julius Jensen"
        ];
    }

    public function testUpdate()
    {
        $this->registerUser($this->userData);
        $this->loginUser($this->userData);
        $request = new Request(
            "POST",
            "/user/update",
            [],
            [
                'email' => 'jj@hefa.dk',
                'name' => 'Simon Jensen'
            ],
            [],
            [],
            [],
            $this->nativeSession
        );
        $this->userController->update($request);
        $updatedUser = $this->authService->requireUser();
        $this->assertEquals('jj@hefa.dk', $updatedUser->email, "User name should match the updated name");
        $this->assertEquals('Simon Jensen', $updatedUser->name, "User email should match the updated email");
    }

    public function testDelete()
    {
        $this->registerUser($this->userData);
        $this->loginUser($this->userData);
        $request = new Request(
            "POST",
            "/user/delete",
            [],
            [],
            [],
            [],
            [],
            $this->nativeSession
        );
        $user = $this->authService->requireUser();
        $this->userController->delete($request);
        $this->assertIsNotInt($this->nativeSession->get('user_id'), "User ID should not be set after deletion");
        $this->assertFalse($this->userRepo->existsById($user->id), "User should not exist after deletion");
    }
}
