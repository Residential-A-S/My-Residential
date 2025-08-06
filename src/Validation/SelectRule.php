<?php

namespace src\Validation;

use src\Exceptions\ValidationException;

final class SelectRule extends AbstractRule
{
    public function __construct(private readonly array $values)
    {
    }

    /**
     * @throws ValidationException
     */
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
