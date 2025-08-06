<?php

namespace src\Exceptions;

final class AuthenticationException extends BaseException
{
    public const int USER_NOT_FOUND = 1;
    public const int INVALID_PASSWORD = 2;
    public const int MUST_BE_LOGGED_IN = 3;

    private const array MESSAGES = [
        self::USER_NOT_FOUND => 'Invalid authentication credentials',
        self::INVALID_PASSWORD => 'Invalid authentication credentials',
        self::MUST_BE_LOGGED_IN => 'You must be logged in to perform this action.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::USER_NOT_FOUND => 401, // Unauthorized
        self::INVALID_PASSWORD => 401, // Unauthorized
        self::MUST_BE_LOGGED_IN => 401, // Unauthorized
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled authentication error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
