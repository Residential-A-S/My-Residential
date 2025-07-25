<?php

namespace src\Validation;

use src\Exceptions\ValidationException;

final readonly class SelectRule implements RuleInterface
{
    public function __construct(private array $values) {}

    public function validate(mixed $value): void
    {
        if (!in_array($value, $this->values, true)) {
            throw new ValidationException(ValidationException::INVALID_SELECT);
        }
    }

    public function toHtmlAttributes(): array
    {
        return ['pattern' => "[A-Za-z0-9]+"];
    }
}