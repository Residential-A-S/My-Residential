<?php

namespace Adapter\Http\Form\Validation;

use Adapter\Exception\ValidationException;

final class AlphaNumericRule extends AbstractRule
{
    /**
     * @throws ValidationException
     */
    public function validate(mixed $value): void
    {
        if (!isset($value) || !ctype_alnum($value)) {
            throw new ValidationException(ValidationException::ALPHA_NUMERIC);
        }
    }

    public function toHtmlAttributes(): array
    {
        return ['pattern' => "[A-Za-z0-9]+", 'type' => 'text'];
    }
}
