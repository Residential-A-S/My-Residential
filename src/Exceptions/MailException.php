<?php

namespace src\Exceptions;

final class MailException extends BaseException
{
    public const int TEMPLATE_ERROR = 1;
    public const int MAIL_NOT_SENT = 2;

    private const array MESSAGES = [
        self::TEMPLATE_ERROR => 'Email template not found or invalid.',
        self::MAIL_NOT_SENT => 'Failed to send email.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::TEMPLATE_ERROR => 500,
        self::MAIL_NOT_SENT => 500,
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled mail error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
