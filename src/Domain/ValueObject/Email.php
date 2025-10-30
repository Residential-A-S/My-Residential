<?php

namespace Domain\ValueObject;

use Domain\Exception\EmailException;

final readonly class Email
{
    /**
     * @throws EmailException
     */
    public function __construct(public string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new EmailException(EmailException::EMAIL_INVALID);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}