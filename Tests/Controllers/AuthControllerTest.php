<?php

namespace Tests\Controllers;

use src\Core\Request;
use src\Exceptions\ValidationException;
use Tests\BaseTest;

class AuthControllerTest extends BaseTest
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
     * @throws ValidationException
     */
    public function testLogin() {
        $this->registerUser($this->userData);
        $this->loginUser($this->userData);
        $this->assertIsInt($this->nativeSession->get('user_id'));
    }

    /**
     * @throws ValidationException
     */
    public function testRegister() {
        $this->registerUser($this->userData);
        $this->assertTrue($this->userRepo->existsByEmail($this->userData['email']));
    }

    /**
     * @throws ValidationException
     */
    public function testLogout() {
        $this->registerUser($this->userData);
        $this->loginUser($this->userData);
        $request = new Request(
            "POST",
            "/logout",
            [],
            [],
            [],
            [],
            [],
            $this->nativeSession
        );
        $this->authCtrl->logout($request);
        $this->assertIsnotInt($this->nativeSession->get('user_id'));
    }
}
