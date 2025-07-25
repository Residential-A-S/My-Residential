<?php
namespace src\Exceptions;

use RuntimeException;
use Throwable;

final class ResponseEncodingException extends RuntimeException
{
    public function __construct(string $message, Throwable $previous)
    {
        parent::__construct($message, 0, $previous);
    }
}