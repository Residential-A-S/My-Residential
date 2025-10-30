<?php

namespace Domain\Exception;

final class TokenGenerationException extends DomainException
{
    public const int COULD_NOT_GENERATE_SECURE_TOKEN  = 1;

    private const array MESSAGES = [
        self::COULD_NOT_GENERATE_SECURE_TOKEN => 'Could not generate a secure token.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::COULD_NOT_GENERATE_SECURE_TOKEN => 500,
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled issue error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}