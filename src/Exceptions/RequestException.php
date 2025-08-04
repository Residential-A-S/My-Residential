<?php
declare(strict_types=1);

namespace src\Exceptions;

/**
 * Represents a 400 Bad Request error.
 */
final class RequestException extends BaseException
{
    public const int INVALID_JSON_FORMAT = 1;

    private const array MESSAGES = [
        self::INVALID_JSON_FORMAT => 'Invalid JSON format in request body.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::INVALID_JSON_FORMAT => 400, // Bad Request
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled request error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
