<?php

namespace Domain\ValueObject;

use Adapter\Http\Exception\ValidationException;

final readonly class Email
{
    /**
     * @throws ValidationException
     */
    public function __construct(public string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException(ValidationException::EMAIL_INVALID);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}