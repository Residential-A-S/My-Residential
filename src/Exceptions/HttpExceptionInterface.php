<?php

namespace src\Exceptions;

interface HttpExceptionInterface {
    public function getHttpStatusCode(): int;
}