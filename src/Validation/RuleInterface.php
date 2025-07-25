<?php

namespace src\Validation;

use src\Exceptions\ValidationException;

interface RuleInterface {
    /**
     * Validate the given value.
     * @Throws ValidationException if validation fails.
     */
    public function validate(mixed $value): void;

    /**
     * Convert this rule into zero-or-more HTML attributes.
     * e.g. ['required'=>true], ['minlength'=>5], ...
     */
    public function toHtmlAttributes(): array;
}