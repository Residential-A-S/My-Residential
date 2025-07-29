<?php

namespace src\Exceptions;

final class UserException extends BaseException
{
    public const int CREATE_FAILED = 1;
    public const int FIND_FAILED = 2;
    public const int UPDATE_FAILED = 3;
    public const int DELETE_FAILED = 4;

    private const array MESSAGES = [
        self::CREATE_FAILED => 'Failed to create user.',
        self::FIND_FAILED => 'Failed to find user.',
        self::UPDATE_FAILED => 'Failed to update user.',
        self::DELETE_FAILED => 'Failed to delete user.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::CREATE_FAILED => 500, // Internal Server Error
        self::FIND_FAILED => 404, // Not Found
        self::UPDATE_FAILED => 500, // Internal Server Error
        self::DELETE_FAILED => 500, // Internal Server Error
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled user error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}