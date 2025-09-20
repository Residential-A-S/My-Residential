<?php

namespace Shared\Exception;

final class ServerException extends BaseException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
