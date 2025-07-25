<?php

namespace src\Validation;

use src\Exceptions\ValidationException;

final readonly class AlphaNumericRule implements RuleInterface {


    public function validate(mixed $value): void
    {
        if (!isset($value) || !ctype_alnum($value)) {
            throw new ValidationException(ValidationException::ALPHA_NUMERIC);
        }
    }

    public function toHtmlAttributes(): array
    {
        return ['pattern' => "[A-Za-z0-9]+"];
    }
}