<?php

namespace Adapter\Exception;

use Shared\Exception\BaseException;

final class DatabaseException extends BaseException
{
    public const int CONNECTION_FAILED = 1;
    public const int QUERY_FAILED = 2;

    private const array MESSAGES = [
        self::CONNECTION_FAILED => 'Database connection failed.',
        self::QUERY_FAILED => 'Database query failed.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::CONNECTION_FAILED => 500,
        self::QUERY_FAILED => 500,
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled database error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}