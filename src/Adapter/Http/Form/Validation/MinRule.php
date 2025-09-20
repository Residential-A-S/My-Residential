<?php

namespace src\Validation;

use Adapter\Http\Exception\ValidationException;

final class MinRule extends AbstractRule
{
    public function __construct(private readonly int $min)
    {
    }

    /**
     * @throws ValidationException
     */
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
