<?php
declare(strict_types=1);

namespace src\Exceptions;

use RuntimeException;

final class NotFoundException extends RuntimeException implements HttpExceptionInterface
{
    public const int STUDENT_NOT_FOUND = 1;
    public const int ITEM_NOT_FOUND = 2;
    public const int TYPE_NOT_FOUND = 3;


    private const int HTTP_STATUS_CODE = 404; // Not Found
    public int $reasonCode;
    public function __construct(int $reasonCode)
    {
        $this->reasonCode = $reasonCode;
        parent::__construct($this->buildMessage($reasonCode));

    }

    private function buildMessage(int $code): string
    {
        return match ($code) {
            self::STUDENT_NOT_FOUND => "Student not found",
            self::ITEM_NOT_FOUND => "Item not found",
            self::TYPE_NOT_FOUND => "Item type not found",
        };
    }

    public function getHttpStatusCode(): int {
        return $this::HTTP_STATUS_CODE;
    }
}
