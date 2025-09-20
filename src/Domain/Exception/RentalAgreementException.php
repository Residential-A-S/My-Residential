<?php

namespace Domain\Exception;

use Shared\Exception\BaseException;

final class RentalAgreementException extends BaseException
{
    public const int RENTAL_AGREEMENT_NOT_FOUND = 1;
    public const int RENTAL_AGREEMENT_CREATE_FAILED = 2;
    public const int RENTAL_AGREEMENT_DOCUMENT_NOT_FOUND = 3;
    public const int RENTAL_AGREEMENT_DOCUMENT_CREATE_FAILED = 4;
    public const int RENTAL_AGREEMENT_PAYMENT_NOT_FOUND = 5;
    public const int RENTAL_AGREEMENT_PAYMENT_CREATE_FAILED = 6;

    private const array MESSAGES = [
        self::RENTAL_AGREEMENT_NOT_FOUND => 'Rental agreement not found.',
        self::RENTAL_AGREEMENT_CREATE_FAILED => 'Failed to create rental agreement.',
        self::RENTAL_AGREEMENT_DOCUMENT_NOT_FOUND => 'Rental agreement document not found.',
        self::RENTAL_AGREEMENT_DOCUMENT_CREATE_FAILED => 'Failed to create rental agreement document.',
        self::RENTAL_AGREEMENT_PAYMENT_NOT_FOUND => 'Rental agreement payment not found.',
        self::RENTAL_AGREEMENT_PAYMENT_CREATE_FAILED => 'Failed to create rental agreement payment.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::RENTAL_AGREEMENT_NOT_FOUND => 404,
        self::RENTAL_AGREEMENT_CREATE_FAILED => 500,
        self::RENTAL_AGREEMENT_DOCUMENT_NOT_FOUND => 404,
        self::RENTAL_AGREEMENT_DOCUMENT_CREATE_FAILED => 500,
        self::RENTAL_AGREEMENT_PAYMENT_NOT_FOUND => 404,
        self::RENTAL_AGREEMENT_PAYMENT_CREATE_FAILED => 500,
    ];

    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled rental agreement error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
