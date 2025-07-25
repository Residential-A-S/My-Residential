<?php

namespace src\Exceptions;

use RuntimeException;

final class AuthenticationException extends RuntimeException implements HttpExceptionInterface
{
    public const int USER_NOT_FOUND = 1;
    public const int INVALID_PASSWORD = 2;
    public const int MUST_BE_LOGGED_IN = 3;


    private const int HTTP_STATUS_CODE = 401; // Unauthorized
    public int $reasonCode;

    public function __construct(int $reasonCode)
    {
        $this->reasonCode = $reasonCode;
        parent::__construct($this->buildMessage($reasonCode));
    }

    private function buildMessage(int $code): string
    {
        return match ($code) {
            self::USER_NOT_FOUND, self::INVALID_PASSWORD => 'Invalid authentication credentials',
            self::MUST_BE_LOGGED_IN => 'You must be logged in to perform this action',
        };
    }

    public function getHttpStatusCode(): int {
        return $this::HTTP_STATUS_CODE;
    }
}