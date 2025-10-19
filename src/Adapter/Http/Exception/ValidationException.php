<?php

namespace Adapter\Http\Exception;

use Shared\Exception\BaseException;

final class ValidationException extends BaseException
{
    public const int FORM_VALIDATION = 1;

    public const int REQUIRED = 2;
    public const int ALPHA_NUMERIC = 3;
    public const int MIN = 4;
    public const int MAX = 5;
    public const int NUMBER = 6;
    public const int STRING = 7;
    public const int INVALID_SELECT = 8;
    public const int INTEGER = 9;

    public const int EMAIL_INVALID = 10;

    public const int PASSWORD_STRENGTH = 11;
    public const int PASSWORD_INVALID = 12;
    public const int PASSWORDS_DO_NOT_MATCH = 13;

    public const int Phone = 14;

    private const array MESSAGES = [
        self::FORM_VALIDATION => 'Form validation failed.',
        self::REQUIRED => 'This field is required.',
        self::ALPHA_NUMERIC => 'Field must only contain letters and numbers characters.',
        self::INTEGER => 'Field must be an integer.',
        self::MIN => 'Field value is too short.',
        self::MAX => 'Field value is too long.',
        self::NUMBER => 'Field must be a number.',
        self::STRING => 'Field must be a string.',
        self::INVALID_SELECT => 'Invalid selection made.',
        self::EMAIL_INVALID => 'Email is invalid.',
        self::PASSWORD_STRENGTH => '
            Password must contain at least one uppercase letter, 
            one lowercase letter, one digit, one special character, 
            and be at least 8 characters long.
            ',
        self::PASSWORD_INVALID => 'Password is invalid.',
        self::PASSWORDS_DO_NOT_MATCH => 'Passwords do not match.',
        self::Phone => 'Invalid phone number format.'
    ];

    private const array HTTP_STATUS_CODES = [
        self::FORM_VALIDATION => 422, // Unprocessable Entity
        self::REQUIRED => 422,
        self::ALPHA_NUMERIC => 422,
        self::MIN => 422,
        self::MAX => 422,
        self::NUMBER => 422,
        self::STRING => 422,
        self::INVALID_SELECT => 422,
        self::EMAIL_INVALID => 422,
        self::PASSWORD_STRENGTH => 422,
        self::PASSWORD_INVALID => 422,
        self::PASSWORDS_DO_NOT_MATCH => 422,
        self::Phone => 422
    ];
    public function __construct(int $code, array $errors = [])
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled validation error occurred.';
        if (!empty($errors)) {
            $message .= ' Errors: ' . implode(', ', $errors);
        }
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
