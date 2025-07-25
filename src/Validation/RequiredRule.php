<?php

namespace src\Validation;

use src\Exceptions\ValidationException;

final readonly class RequiredRule implements RuleInterface {

    public function validate(mixed $value): void
    {
        if (!isset($value) || trim((string)$value) === '') {
            throw new ValidationException(ValidationException::REQUIRED);
        }
    }

    public function toHtmlAttributes(): array
    {
        return ['required' => true];
    }
}