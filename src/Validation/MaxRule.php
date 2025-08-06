<?php

namespace src\Validation;

use src\Exceptions\ValidationException;

final class MaxRule extends AbstractRule
{
    public function __construct(private readonly int $max)
    {
    }

    /**
     * @throws ValidationException
     */
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
