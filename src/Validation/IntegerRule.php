<?php

namespace src\Validation;

use src\Exceptions\ValidationException;

final class IntegerRule extends AbstractRule
{
    public function __construct()
    {
    }

    /**
     * @throws ValidationException
     */
    public function validate(mixed $value): void
    {
        if (!is_int($value)) {
            throw new ValidationException(ValidationException::INTEGER);
        }
    }

    public function toHtmlAttributes(): array
    {
        return ['type' => 'number'];
    }
}
