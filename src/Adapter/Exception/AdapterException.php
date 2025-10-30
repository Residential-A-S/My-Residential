<?php

namespace Adapter\Exception;

use Exception;
use Throwable;

abstract class AdapterException extends Exception
{
    private int $httpCode;

    public function __construct(
        string $message,
        int $httpCode = 500,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $errorLogFile = __DIR__ . '/../Core/errors.log';
        if (!file_exists($errorLogFile)) {
            touch($errorLogFile);
        }
        file_put_contents($errorLogFile, date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
        $this->httpCode = $httpCode;
        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpCode;
    }
}
