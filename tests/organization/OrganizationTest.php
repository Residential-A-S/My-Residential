<?php

namespace organization;

use src\Models\Organization;
use src\Models\User;
use PHPUnit\Framework\TestCase;
use user\UserTest;

class OrganizationTest extends TestCase
{
    private array $mockOrganizationData = [
        "name"    => "test@propeteer.app",
        "description" => "test1234",
        "properties" => [],
        "users" => []
    ];

    public function testOrganization(): void
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
        $result = User::create(
            UserTest::$mockUserData['email'],
            UserTest::$mockUserData['password'],
            UserTest::$mockUserData['role']
        );
        $user = User::getByEmail(UserTest::$mockUserData['email']);
        $org = Organization::create(
            $this->mockOrganizationData['name'],
            $this->mockOrganizationData['description'],
            $user
        );
        $this->assertNotFalse($org, "Org creation failed");
        $this->assertEquals($this->mockOrganizationData['name'], $org->name, "Org name does not match");
        $this->assertEquals($this->mockOrganizationData['description'], $org->description, "Org description does not match");
    }

    private function testDelete(): void
    {
        $user = User::getUserByEmail($this->mockUserData['email']);
        $this->assertNotNull($user, "User not found");
        $result = $user->delete();
        $this->assertTrue($result, "User deletion failed");
    }
}
