<?php

namespace Domain\Exception;

use Shared\Exception\BaseException;

final class PasswordResetException extends BaseException
{
    public const int TOKEN_NOT_SECURE = 1;

    private const array MESSAGES = [
        self::TOKEN_NOT_SECURE => 'The provided password reset token is not secure.',
    ];

    public function __construct(int $code)
    {
        parent::__construct(
            self::MESSAGES[$code] ?? 'Unhandled password reset error.',
            $code,
            500
        );
    }
}
