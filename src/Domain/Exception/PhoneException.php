<?php

namespace Domain\Exception;

final class PhoneException extends DomainException
{
    public const int PHONE_INVALID = 1;

    private const array MESSAGES = [
        self::PHONE_INVALID => 'The provided phone number is invalid.',
    ];

    public function __construct(int $code)
    {
        parent::__construct(
            self::MESSAGES[$code] ?? 'Unhandled phone reset error.',
            $code,
            500
        );
    }
}
