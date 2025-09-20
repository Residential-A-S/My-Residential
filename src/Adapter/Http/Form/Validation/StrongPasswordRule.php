<?php

namespace src\Validation;

use Adapter\Http\Exception\ValidationException;

final class StrongPasswordRule extends AbstractRule
{
    /**
     * @throws ValidationException
     */
    public function validate(mixed $value): void
    {
        $strongPasswordPattern = '/
            ^                         # start of string
            (?=.*[A-Z])               # at least one uppercase letter
            (?=.*[a-z])               # at least one lowercase letter
            (?=.*\d)                  # at least one digit
            (?=.*[@$!%*?&])           # at least one special character
            [A-Za-z\d@$!%*?&]{8,}     # allowed chars and minimum length 8
            $                         # end of string
        /x';

        if (!preg_match($strongPasswordPattern, $value)) {
            throw new ValidationException(ValidationException::PASSWORD_STRENGTH);
        }
    }

    public function toHtmlAttributes(): array
    {
        return ['pattern' => "[A-Za-z0-9]+"];
    }
}
