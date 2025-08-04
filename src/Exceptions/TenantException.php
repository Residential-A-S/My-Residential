<?php

namespace src\Exceptions;

final class TenantException extends BaseException
{
    public const int NOT_FOUND = 1;
    public const int CREATE_FAILED = 2;

    private const array MESSAGES = [
        self::NOT_FOUND => 'Tenant not found.',
        self::CREATE_FAILED => 'Failed to create tenant.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::NOT_FOUND => 404,
        self::CREATE_FAILED => 400,
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled tenant error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}