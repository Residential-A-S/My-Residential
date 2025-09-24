<?php

namespace Domain\Exception;

use Shared\Exception\BaseException;

final class IssueException extends BaseException
{
    public const int NOT_FOUND = 1;
    public const int CREATE_FAILED = 2;

    private const array MESSAGES = [
        self::NOT_FOUND => 'CreateIssueCommand not found.',
        self::CREATE_FAILED => 'Failed to create issue.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::NOT_FOUND => 404,
        self::CREATE_FAILED => 400,
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled issue error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
