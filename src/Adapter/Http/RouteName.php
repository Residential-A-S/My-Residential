<?php

namespace Adapter\Http;

enum RouteName
{
    case Web_Home;
    case Web_Login;
    case Web_Properties;

    case Api_Login;
    case Api_Logout;
    case Api_Register;
    case Api_Forgot_Password_Send_Verification;
    case Api_Forgot_Password_Reset;

    case Api_Change_Password;
    case Api_User_Update;
    case Api_User_Delete;

    case Api_Property_Create;
    case Api_Property_Update;
    case Api_Property_Delete;

    case Api_Organization_Create;
    case Api_Organization_Update;
    case Api_Organization_Delete;

    case Api_Issue_Create;
    case Api_Issue_Update;
    case Api_Issue_Delete;

    case Api_Payment_Create;
    case Api_Payment_Update;
    case Api_Payment_Delete;

    case Api_Rental_Agreement_Create;
    case Api_Rental_Agreement_Update;
    case Api_Rental_Agreement_Delete;

    public function getMethod(): string
    {
        return match ($this) {
            self::Web_Home, self::Web_Login, self::Web_Properties => 'GET',

            self::Api_Login, self::Api_Logout, self::Api_Register, self::Api_Forgot_Password_Send_Verification,
            self::Api_Property_Create, self::Api_Organization_Create, self::Api_Forgot_Password_Reset,
            self::Api_Issue_Create, self::Api_Payment_Create, self::Api_Rental_Agreement_Create => 'POST',

            self::Api_User_Update, self::Api_Property_Update, self::Api_Organization_Update,
            self::Api_Change_Password, self::Api_Issue_Update, self::Api_Payment_Update,
            self::Api_Rental_Agreement_Update => 'PUT',

            self::Api_User_Delete, self::Api_Property_Delete, self::Api_Organization_Delete,
            self::Api_Issue_Delete, self::Api_Payment_Delete, self::Api_Rental_Agreement_Delete => 'DELETE',
        };
    }

    public function getPath(): string
    {
        return match ($this) {
            self::Web_Home => '/',
            self::Web_Login,
            self::Web_Properties => '/properties',

            self::Api_Login => '/api/v1/users/login',
            self::Api_Logout => '/api/v1/users/logout',
            self::Api_Register => '/api/v1/users/register',
            self::Api_Forgot_Password_Send_Verification => '/api/v1/users/forgot-password/send-verification',
            self::Api_Forgot_Password_Reset => '/api/v1/users/forgot-password/reset',
            self::Api_User_Update => '/api/v1/users/update',
            self::Api_User_Delete => '/api/v1/users/delete',
            self::Api_Change_Password => '/api/v1/users/change-password',

            self::Api_Property_Create => '/api/v1/properties/create',
            self::Api_Property_Update => '/api/v1/properties/update',
            self::Api_Property_Delete => '/api/v1/properties/delete',

            self::Api_Organization_Create => '/api/v1/organizations/create',
            self::Api_Organization_Update => '/api/v1/organizations/update',
            self::Api_Organization_Delete => '/api/v1/organizations/delete',

            self::Api_Issue_Create => '/api/v1/issues/create',
            self::Api_Issue_Update => '/api/v1/issues/update',
            self::Api_Issue_Delete => '/api/v1/issues/delete',

            self::Api_Payment_Create => '/api/v1/payments/create',
            self::Api_Payment_Update => '/api/v1/payments/update',
            self::Api_Payment_Delete => '/api/v1/payments/delete',

            self::Api_Rental_Agreement_Create => '/api/v1/rental-agreements/create',
            self::Api_Rental_Agreement_Update => '/api/v1/rental-agreements/update',
            self::Api_Rental_Agreement_Delete => '/api/v1/rental-agreements/delete',
        };
    }
}
