<?php

namespace src\Exceptions;

final class ConflictException extends BaseException
{
    public const int EMAIL_ALREADY_EXISTS = 1;

    private const array MESSAGES = [
        self::EMAIL_ALREADY_EXISTS => 'Email already exists.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::EMAIL_ALREADY_EXISTS => 409, // Conflict
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled conflict error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 409;
        parent::__construct($message, $httpCode);
    }
}