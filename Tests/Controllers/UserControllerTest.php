<?php

namespace Tests\Controllers;

use Adapter\Exception\ValidationException;
use Adapter\Http\Request;
use Domain\Exception\UserException;
use Shared\Exception\ServerException;
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

    /**
     * @throws ServerException
     * @throws UserException
     * @throws ValidationException
     */
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

    /**
     * @throws ValidationException
     * @throws ServerException
     * @throws UserException
     */
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

    /**
     * @throws ValidationException
     * @throws UserException
     * @throws ServerException
     */
    public function testUpdatePassword()
    {
        $this->registerUser($this->userData);
        $this->loginUser($this->userData);
        $request = new Request(
            "POST",
            "/user/change-password",
            [],
            ['password' => 'NewPassword123!'],
            [],
            [],
            [],
            $this->nativeSession
        );
        $this->userController->updatePassword($request);
        $user = $this->authService->requireUser();
        $this->assertTrue(password_verify('NewPassword123!', $user->passwordHash), "User password should be updated successfully");
    }
}
