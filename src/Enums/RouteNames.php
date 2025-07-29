<?php

namespace src\Enums;

enum RouteNames
{
    case Home;
    case Login_GET;
    case Properties;

    case Login_POST;
    case Logout;
    case Register;
    case User_Update;
    case User_Delete;

    case Property_Create;
    case Property_Update;
    case Property_Delete;

    case Organization_Create;
    case Organization_Update;
    case Organization_Delete;

    public function getMethod(): string
    {
        return match ($this) {
            self::Home, self::Login_GET, self::Properties => 'GET',

            self::Login_POST, self::Logout, self::Register,
            self::User_Update, self::User_Delete,
            self::Property_Create, self::Property_Update, self::Property_Delete,
            self::Organization_Create, self::Organization_Update, self::Organization_Delete => 'POST'
        };
    }

    public function getPath(): string
    {
        return match ($this) {
            self::Home => '/',
            self::Login_GET,
            self::Login_POST => '/login',
            self::Logout => '/logout',
            self::Register => '/register',
            self::User_Update => '/user/update',
            self::User_Delete => '/user/delete',
        };
    }
}