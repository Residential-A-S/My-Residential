<?php

namespace property;

use core\Request;
use models\Property;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    private array $mockPropertyData = [
        "country" => "Germany",
        "postal_code" => "12345",
        "city" => "Berlin",
        "address" => "Musterstrasse 1",
    ];

    public function testProperty(): void
    {
        $this->testHelperMethods();
    }

    public function testHelperMethods(): void
    {
        $this->testCreate();
        $this->testDelete();
    }

    private function testCreate(): void
    {
        $result = Property::create(
            $this->mockPropertyData['country'],
            $this->mockPropertyData['postal_code'],
            $this->mockPropertyData['city'],
            $this->mockPropertyData['address'],

        );
        $this->assertTrue($result, "User creation failed");
        $user = User::getUserByEmail($this->mockUserData['email']);
        $this->assertNotNull($user, "User not found");
        $this->assertEquals($this->mockUserData['email'], $user->email, "User email does not match"); //Test email
        $this->assertEquals($this->mockUserData['role'], $user->role, "User role does not match"); //Test role
        $this->assertTrue(
            password_verify(
                $this->mockUserData['password'],
                $user->password
            ),
            "User password does not match"
        ); //Test password
        $this->assertEquals($user->id, $user->id, "User ID does not match"); //Test ID
    }

    private function testDelete(): void
    {
        $user = User::getUserByEmail($this->mockUserData['email']);
        $this->assertNotNull($user, "User not found");
        $result = $user->delete();
        $this->assertTrue($result, "User deletion failed");
    }
}
