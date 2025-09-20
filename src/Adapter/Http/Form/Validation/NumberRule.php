<?php

namespace src\Validation;

use Adapter\Http\Exception\ValidationException;

final class NumberRule extends AbstractRule
{
    public function __construct()
    {
    }

    /**
     * @throws ValidationException
     */
    public function validate(mixed $value): void
    {
        if (!is_numeric($value)) {
            throw new ValidationException(ValidationException::NUMBER);
        }
    }

    public function toHtmlAttributes(): array
    {
        return ['type' => 'number'];
    }
}
