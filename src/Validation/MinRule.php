<?php

namespace src\Validation;

use src\Exceptions\ValidationException;

final readonly class MinRule implements RuleInterface {

    public function __construct(private int $min) {}

    public function validate(mixed $value): void
    {
        if (strlen((string)$value) < $this->min) {
            throw new ValidationException(ValidationException::MIN);
        }
    }

    public function toHtmlAttributes(): array
    {
        return ['minlength' => $this->min];
    }
}