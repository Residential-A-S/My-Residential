<?php

namespace src\Exceptions;

final class OrganizationException extends BaseException
{
    public const int NOT_FOUND = 1;
    public const int CREATE_FAILED = 2;
    public const int UPDATE_FAILED = 3;
    public const int DELETE_FAILED = 4;
    public const int DELETE_NOT_ALLOWED = 5;
    public const int UPDATE_NOT_ALLOWED = 6;
    public const int ADD_USER_NOT_ALLOWED = 7;
    public const int REMOVE_USER_NOT_ALLOWED = 8;
    public const int USER_ALREADY_IN_ORGANIZATION = 9;
    public const int REMOVE_SELF_NOT_ALLOWED = 10;
    public const int USER_NOT_IN_ORGANIZATION = 11;
    public const int TRANSFER_NOT_ALLOWED = 12;

    private const array MESSAGES = [
        self::CREATE_FAILED => 'Failed to create organization.',
        self::NOT_FOUND => 'Failed to find organization.',
        self::UPDATE_FAILED => 'Failed to update organization.',
        self::DELETE_FAILED => 'Failed to delete organization.',
        self::DELETE_NOT_ALLOWED => 'You are not allowed to delete this organization.',
        self::UPDATE_NOT_ALLOWED => 'You are not allowed to update this organization.',
        self::ADD_USER_NOT_ALLOWED => 'You are not allowed to add users to this organization.',
        self::REMOVE_USER_NOT_ALLOWED => 'You are not allowed to remove users from this organization.',
        self::USER_ALREADY_IN_ORGANIZATION => 'The user is already a member of this organization.',
        self::REMOVE_SELF_NOT_ALLOWED => 'You cannot remove yourself from the organization.',
        self::USER_NOT_IN_ORGANIZATION => 'The user is not a member of this organization.',
        self::TRANSFER_NOT_ALLOWED => 'You are not allowed to transfer ownership of this organization.',
    ];

    private const array HTTP_STATUS_CODES = [
        self::CREATE_FAILED => 500, // Internal Server Error
        self::NOT_FOUND => 404, // Not Found
        self::UPDATE_FAILED => 500, // Internal Server Error
        self::DELETE_FAILED => 500, // Internal Server Error
        self::DELETE_NOT_ALLOWED => 403, // Forbidden
        self::UPDATE_NOT_ALLOWED => 403, // Forbidden
        self::ADD_USER_NOT_ALLOWED => 403, // Forbidden
        self::REMOVE_USER_NOT_ALLOWED => 403, // Forbidden
        self::USER_ALREADY_IN_ORGANIZATION => 409, // Conflict
        self::REMOVE_SELF_NOT_ALLOWED => 403, // Forbidden
        self::USER_NOT_IN_ORGANIZATION => 404, // Not Found
        self::TRANSFER_NOT_ALLOWED => 403, // Forbidden
    ];


    public function __construct(int $code)
    {
        $message = self::MESSAGES[$code] ?? 'An unhandled organization error occurred.';
        $httpCode = self::HTTP_STATUS_CODES[$code] ?? 500;
        parent::__construct($message, $httpCode);
    }
}
