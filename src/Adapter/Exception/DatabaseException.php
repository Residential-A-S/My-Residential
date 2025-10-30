<?php

namespace Adapter\Exception;

final class DatabaseException extends AdapterException
{
    public const int CONNECTION_FAILED = 1;
    public const int QUERY_FAILED = 2;
    public const int RECORD_NOT_FOUND = 3;
    public const int HYDRATION_FAILED = 4;

    private const array MESSAGES = [
        self::CONNECTION_FAILED => 'Database connection failed.',
        self::QUERY_FAILED => 'Database query failed.',
        self::RECORD_NOT_FOUND => 'Requested record not found.',
        self::HYDRATION_FAILED => 'Failed to hydrate database record.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::CONNECTION_FAILED => 500,
        self::QUERY_FAILED => 500,
        self::RECORD_NOT_FOUND => 404,
        self::HYDRATION_FAILED => 500,
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled database error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}