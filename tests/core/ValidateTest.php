<?php

namespace core;

use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    public function testEmailValidation(): void
    {
        // Valid emails
        $validEmails = [
            'user@example.com',
            'john.doe@example.co.uk',
            'user+alias@sub.domain.com',
            'firstname-lastname@domain.io',
            'user_name123@example.net',
            'a@b.co', // minimal valid
        ];

        foreach ($validEmails as $email) {
            $this->assertTrue(Validate::email($email), "Expected valid: $email");
        }

        // Invalid emails
        $invalidEmails = [
            '', // empty
            'plainaddress',
            'missingatsign.com',
            'user@.com',
            'user@com',
            'user@domain..com',
            '@nouser.com',
            'user@domain@domain.com',
            'user@domain,com',
            'user@-domain.com',
            'user@domain-.com',
            'user@.domain.com',
        ];

        foreach ($invalidEmails as $email) {
            $this->assertFalse(Validate::email($email), "Expected invalid: $email");
        }

        for ($i = 0; $i < 100; $i++) {
            $this->assertTrue(
                Validate::email("test" . uniqid() . "@propeteer.app"),
                "Expected valid: test" . uniqid() . "@propeteer.app"
            );
        }
    }

    public function testStringValidation(): void
    {
        // Valid strings
        $validStrings = [
            'Hello, World!',
            'Valid String',
            '1234567890',
            'Special characters !@#$%^&*()',
            'Whitespace   ',
            'Leading and trailing spaces ',
        ];

        foreach ($validStrings as $string) {
            $this->assertTrue(Validate::string($string), "Expected valid: $string");
        }

        // Invalid strings
        $invalidStrings = [
            '', // empty string
            null, // null value
            12345, // integer
            12.34, // float
            [], // empty array
            ['a', 'b'], // non-string array
        ];

        foreach ($invalidStrings as $string) {
            $this->assertFalse(Validate::string($string), "Expected invalid: " . json_encode($string));
        }
    }

    public function testPasswordValidation(): void
    {
        // Valid passwords
        $validPasswords = [
            'Password1@',
            'StrongPass!123',
            'Another$Valid1',
            'Complex!Password2',
            'Valid1234$',
        ];

        foreach ($validPasswords as $password) {
            $this->assertTrue(Validate::password($password), "Expected valid: $password");
        }

        // Invalid passwords
        $invalidPasswords = [
            '', // empty
            'short', // too short
            'NoSpecialChar1', // no special character
            'NoNumber!', // no number
            'nouppercase1@', // no uppercase letter
            'NOLOWERCASE1@', // no lowercase letter
            '12345678!', // only numbers and special characters
        ];

        foreach ($invalidPasswords as $password) {
            $this->assertFalse(Validate::password($password), "Expected invalid: $password");
        }
    }

    public function testRoleValidation(): void
    {
        // Valid roles
        $validRoles = [
            'admin'
        ];

        foreach ($validRoles as $role) {
            $this->assertTrue(Validate::role($role), "Expected valid: $role");
        }

        // Invalid roles
        $invalidRoles = [
            '', // empty
            'guest', // not in valid roles
            'superuser', // not in valid roles
            '12345', // numeric string
            null, // null value
        ];

        foreach ($invalidRoles as $role) {
            $this->assertFalse(Validate::role($role), "Expected invalid: " . json_encode($role));
        }
    }
}
