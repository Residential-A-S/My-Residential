<?php

namespace Adapter\Http\Form\Validation;

use Adapter\Exception\ValidationException;

abstract class AbstractRule
{
    /**
     * Validate the given value.
     *
     * @throws ValidationException
     */
    public function validate(mixed $value): void
    {
        throw new ValidationException(ValidationException::FORM_VALIDATION);
    }

    /**
     * Convert this rule into zero-or-more HTML attributes.
     * e.g. ['required'=>true], ['minlength'=>5], ...
     */
    public function toHtmlAttributes(): array
    {
        return [];
    }
}
