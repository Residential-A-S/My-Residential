<?php

namespace request;

use Core\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testRequest(): void
    {
        $this->testRequestConstructor();
        $this->testRequestError();
        $this->testRequestSuccess();
    }

    private function testRequestConstructor(): void
    {
        $request = new Request();
        $this->assertTrue($request->success);
    }

    private function testRequestError(): void
    {
        $request = new Request();
        $request->error("invalid_request");
        $this->assertFalse($request->success);
    }

    private function testRequestSuccess(): void
    {
        $request = new Request();
        $request->success("valid_request");
        $this->assertTrue($request->success);
    }
}
