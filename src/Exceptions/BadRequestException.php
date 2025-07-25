<?php
declare(strict_types=1);

namespace src\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Represents a 400 Bad Request error.
 */
final class BadRequestException extends RuntimeException
{
    public function __construct(
        string $message = 'Bad Request',
        int $code = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
