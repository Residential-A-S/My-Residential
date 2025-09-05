<?php

namespace src\Exceptions;

final class UserException extends BaseException
{
    public const int NOT_FOUND = 1;
    public const int CREATE_FAILED = 2;
    public const int UPDATE_FAILED = 3;
    public const int DELETE_FAILED = 4;
    public const int EMAIL_ALREADY_EXISTS = 5;
    public const int PASSWORDS_DO_NOT_MATCH = 6;

    private const array MESSAGES = [
        self::NOT_FOUND => 'User not found.',
        self::CREATE_FAILED => 'Failed to create user.',
        self::UPDATE_FAILED => 'Failed to update user.',
        self::DELETE_FAILED => 'Failed to delete user.',
        self::EMAIL_ALREADY_EXISTS => 'Email already exists.',
        self::PASSWORDS_DO_NOT_MATCH => 'Passwords do not match.',
    ];

    private const array HTTP_STATUS_CODES      = [
        self::NOT_FOUND => 404, // Not Found
        self::CREATE_FAILED => 500, // Internal Server Error
        self::UPDATE_FAILED => 500, // Internal Server Error
        self::DELETE_FAILED => 500, // Internal Server Error
        self::EMAIL_ALREADY_EXISTS => 409, // Conflict
        self::PASSWORDS_DO_NOT_MATCH => 400, // Bad Request
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled user error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
