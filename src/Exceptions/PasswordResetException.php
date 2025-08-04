<?php

namespace src\Exceptions;

final class PasswordResetException extends BaseException
{
    public const int EXPIRED_TOKEN = 1;
    public const int INVALID_TOKEN = 2;
    public const int INSERT_FAILED = 3;
    public const int DELETE_FAILED = 4;
    public const int CANNOT_REUSE_PASSWORD = 5;

    private const array MESSAGES = [
        self::EXPIRED_TOKEN => 'The password reset token has expired.',
        self::INVALID_TOKEN => 'The password reset token is invalid.',
        self::INSERT_FAILED => 'Could not store password reset token.',
        self::DELETE_FAILED => 'Could not delete password reset token.',
        self::CANNOT_REUSE_PASSWORD => 'You cannot reuse your previous password.',
    ];

    public function __construct(int $code)
    {
        parent::__construct(
            self::MESSAGES[$code] ?? 'Unhandled password reset error.',
            $code,
            400
        );
    }
}
