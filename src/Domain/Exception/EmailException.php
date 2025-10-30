<?php

namespace Domain\Exception;

final class EmailException extends DomainException
{
    public const int EMAIL_INVALID = 1;

    private const array MESSAGES = [
        self::EMAIL_INVALID => 'The provided email address is invalid.',
    ];

    public function __construct(int $code)
    {
        parent::__construct(
            self::MESSAGES[$code] ?? 'Unhandled email reset error.',
            $code,
            500
        );
    }
}
