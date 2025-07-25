<?php

namespace src\Exceptions;

use src\Exceptions\HttpExceptionInterface;
use RuntimeException;

final class ConflictException extends RuntimeException implements HttpExceptionInterface
{
    public const int USER_ALREADY_EXISTS = 1;


    private const int HTTP_STATUS_CODE = 409; // Conflict
    public int $reasonCode;
    public function __construct(int $reasonCode)
    {
        $this->reasonCode = $reasonCode;
        parent::__construct($this->buildMessage($reasonCode));
    }

    private function buildMessage(int $code): string
    {
        return match ($code) {
            self::USER_ALREADY_EXISTS => "User already exists",
        };
    }

    public function getHttpStatusCode(): int {
        return $this::HTTP_STATUS_CODE;
    }
}