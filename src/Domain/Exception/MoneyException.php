<?php

namespace Domain\Exception;

use Shared\Exception\BaseException;

final class MoneyException extends BaseException
{
    public const int ARITHMETIC_ON_DIFFERENT_CURRENCIES  = 1;

    private const array MESSAGES = [
        self::ARITHMETIC_ON_DIFFERENT_CURRENCIES => 'Cannot perform arithmetic on different currencies.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::ARITHMETIC_ON_DIFFERENT_CURRENCIES => 400,
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled issue error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
