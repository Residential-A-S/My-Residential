<?php

namespace request;

use core\App;
use core\XMLHttpRequest;
use PHPUnit\Framework\TestCase;

class XMLHttpRequestTest extends TestCase
{
    public function testConstruct()
    {
        App::init();
        $params = [
            'action' => 'register',
            'email' => 'julius.jensen02@gmail.com',
            'password' => '6KQr9qPvbk5Cwz8c'
        ];
        $request = new XMLHttpRequest($params);
        $this->assertFalse(App::isLoggedIn());
        $this->assertTrue($request->action === 'register');
        $this->assertTrue($request->success);
    }
}
