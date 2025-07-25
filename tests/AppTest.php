<?php

namespace Core;

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testRespond()
    {
    }

    public function test_e()
    {
    }

    public function test__()
    {
    }

    public function testRun()
    {
    }

    public function testIsLoggedIn()
    {
    }

    public function testInit()
    {
        App::init();
        $this->assertTrue(App::$db === Database::getInstance());
    }

    public function testSetResponseBody()
    {
    }

    public function testSetFailed()
    {
    }
}
