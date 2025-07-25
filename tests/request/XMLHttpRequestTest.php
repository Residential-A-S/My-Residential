<?php

namespace request;

use Core\App;
use Core\Validate;
use Core\XMLHttpRequest;
use src\Models\User;
use PHPUnit\Framework\TestCase;

class XMLHttpRequestTest extends TestCase
{
    private array $request_data_list;
    public function setUp(): void
    {
        $this->request_data_list['register'] = [
            [
                'action' => 'register',
                'email' => 'julius.jensen02@gmail.com',
                'password' => '6KQr9qPvbk5Cwz8c!',
                'repeat_password' => '6KQr9qPvbk5Cwz8c!'
            ]
        ];

    }

    public function tearDown(): void
    {
        foreach ($this->request_data_list['register'] as $request_data) {
            $user = User::getByEmail($request_data['email']);
            $user?->delete();
        }
    }
    public function testRegisterRequest()
    {
        foreach ($this->request_data_list['register'] as $request_data) {
            App::init();
            $this->assertTrue(Validate::email($request_data['email']), 'Email validation failed');
            $this->assertTrue(Validate::password($request_data['password']), 'Password validation failed');
            $this->assertTrue(($request_data['password'] === $request_data['repeat_password']), 'Passwords do not match');
            $request = new XMLHttpRequest($request_data);
            $this->assertFalse(App::isLoggedIn());
            $this->assertTrue($request->action === 'register');;
            if (!$request->success) {
                $this->assertEquals('invalid_register_credentials', App::$response_message_code);
                $this->assertEquals('user_creation_failed', App::$response_message_code);
            } else {
                $this->assertEquals('user_created', App::$response_message_code);
            }
        }
    }

    public function testLoginRequest()
    {

    }
}
