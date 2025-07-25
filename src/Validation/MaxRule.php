<?php

namespace src\Validation;

use src\Exceptions\ValidationException;

final readonly class MaxRule implements RuleInterface {

    public function __construct(private int $max) {}

    public function validate(mixed $value): void
    {
        if (strlen((string)$value) > $this->max) {
            throw new ValidationException(ValidationException::MAX);
        }
    }

    public function toHtmlAttributes(): array
    {
        return ['maxlength' => $this->max];
    }
}