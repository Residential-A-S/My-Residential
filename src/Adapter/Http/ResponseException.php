<?php

namespace Adapter\Http;

use Shared\Exception\BaseException;

final class ResponseException extends BaseException
{
    public const int JSON_ENCODE_FAILED = 1;

    private const array MESSAGES = [
        self::JSON_ENCODE_FAILED => 'Failed to encode data to JSON format.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::JSON_ENCODE_FAILED => 500, // Internal Server Error
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled request error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
