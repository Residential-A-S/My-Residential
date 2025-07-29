<?php

namespace src\Exceptions;

use RuntimeException;

final class ServerException extends RuntimeException implements HttpExceptionInterface
{
    public const int UNKNOWN_ERROR = 0001;
    public const int DATE_MALFORMED = 0002;
    public const int USER_CREATE_FAILED = 1001;
    public const int USER_FIND_FAILED = 1002;
    public const int USER_UPDATE_FAILED = 1003;
    public const int USER_DELETE_FAILED = 1004;
    public const int PROPERTY_CREATE_FAILED = 2001;
    public const int PROPERTY_FIND_FAILED = 2002;
    public const int PROPERTY_UPDATE_FAILED = 2003;
    public const int PROPERTY_DELETE_FAILED = 2004;
    public const int PROPERTY_ASSIGN_FAILED = 2005;
    public const int ORGANIZATION_CREATE_FAILED = 3001;
    public const int ORGANIZATION_FIND_FAILED = 3002;
    public const int ORGANIZATION_UPDATE_FAILED = 3003;
    public const int ORGANIZATION_DELETE_FAILED = 3004;
    public const int ORGANIZATION_USER_LINK_FAILED = 3005;
    public const int ORGANIZATION_USER_UNLINK_FAILED = 3006;
    public const int ORGANIZATION_PROPERTY_LINK_FAILED = 3007;
    public const int ORGANIZATION_PROPERTY_UNLINK_FAILED = 3008;
    public const int ORGANIZATION_TRANSFER_FAILED = 3009;

    private const int HTTP_STATUS_CODE = 500; // Internal Server Error

    public int $reasonCode;
    public function __construct(int $reasonCode)
    {
        $this->reasonCode = $reasonCode;
        parent::__construct($this->buildMessage($reasonCode));
    }

    private function buildMessage(int $code): string
    {
        return match ($code) {
            self::USER_CREATE_FAILED => 'Failed to create user.',
            self::USER_FIND_FAILED => 'Failed to find user.',
            self::USER_UPDATE_FAILED => 'Failed to update user.',
            self::USER_DELETE_FAILED => 'Failed to delete user.',
            self::PROPERTY_CREATE_FAILED => 'Failed to create property.',
            self::PROPERTY_FIND_FAILED => 'Failed to find property.',
            self::PROPERTY_UPDATE_FAILED => 'Failed to update property.',
            self::PROPERTY_DELETE_FAILED => 'Failed to delete property.',
            self::ORGANIZATION_CREATE_FAILED => 'Failed to create organization.',
            self::ORGANIZATION_FIND_FAILED => 'Failed to find organization.',
            self::ORGANIZATION_UPDATE_FAILED => 'Failed to update organization.',
            self::ORGANIZATION_DELETE_FAILED => 'Failed to delete organization.',
            default => 'An unknown server error occurred.'
        };
    }

    public function getHttpStatusCode(): int {
        return $this::HTTP_STATUS_CODE;
    }
}