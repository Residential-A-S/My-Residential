<?php

namespace src\Exceptions;

use RuntimeException;

final class ValidationException extends RuntimeException implements HttpExceptionInterface
{
    public const int FORM_VALIDATION = 1;

    public const int REQUIRED = 1;
    public const int ALPHA_NUMERIC = 2;
    public const int MIN = 3;
    public const int MAX = 4;
    public const int NUMBER = 5;
    public const int STRING = 6;
    public const int INVALID_SELECT = 7;

    public const int USERNAME_INVALID = 8;

    public const int EMAIL_INVALID = 9;

    public const int PASSWORD_STRENGTH = 10;
    public const int PASSWORD_INVALID = 11;
    public const int PASSWORDS_DO_NOT_MATCH = 12;


    private const int HTTP_STATUS_CODE = 422; // Unprocessable Entity
    public float $reasonCode;
    public array $errors;

    public function __construct(int $reasonCode, array $errors = []) {
        $this->reasonCode = $reasonCode;
        $this->errors = $errors;
        $message = $this->buildMessage($reasonCode);
        if (is_array($message)) {
            $message = json_encode($message);
        }
        parent::__construct($message);
    }


    private function buildMessage(int $code): string|array
    {
        return match ($code) {
            self::FORM_VALIDATION => $this->errors,

            self::REQUIRED => "This field is required",
            self::ALPHA_NUMERIC => "Field must only contain letters and numbers characters",
            self::MIN => "Field value is too short",
            self::MAX => "Field value is too long",
            self::NUMBER => "Field must be a number",
            self::STRING => "Field must be a string",
            self::INVALID_SELECT => "Invalid selection made",

            self::USERNAME_INVALID => "Username is invalid",

            self::EMAIL_INVALID => "Email is invalid",

            self::PASSWORD_STRENGTH => "Password must contain at least one uppercase letter, one lowercase letter, one digit, one special character, and be at least 8 characters long",
            self::PASSWORD_INVALID => "Password is invalid",
            self::PASSWORDS_DO_NOT_MATCH => "Passwords do not match",
        };
    }

    public function getHttpStatusCode(): int {
        return $this::HTTP_STATUS_CODE;
    }
}