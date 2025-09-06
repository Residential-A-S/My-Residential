<?php

use src\Controllers\Api\AuthController;
use src\Controllers\Api\OrganizationController;
use src\Controllers\Api\PasswordResetController;
use src\Controllers\Api\PropertyController;
use src\Controllers\Api\UserController;
use src\Core\Container;
use src\Core\Router;
use src\Enums\RouteName;

return static function (Router $r, Container $c): void {
    $r->map(RouteName::Api_Login, [$c->get(AuthController::class), 'login']);
    $r->map(RouteName::Api_Logout, [$c->get(AuthController::class), 'logout']);
    $r->map(RouteName::Api_Register, [$c->get(AuthController::class), 'register']);
    $r->map(
        RouteName::Api_Forgot_Password_Send_Verification,
        [$c->get(PasswordResetController::class), 'sendVerification']
    );
    $r->map(RouteName::Api_Forgot_Password_Reset, [$c->get(PasswordResetController::class), 'resetPassword']);
    $r->map(RouteName::Api_User_Update, [$c->get(UserController::class), 'update']);
    $r->map(RouteName::Api_User_Delete, [$c->get(UserController::class), 'delete']);

    $r->map(RouteName::Api_Property_Create, [$c->get(PropertyController::class), 'create']);
    $r->map(RouteName::Api_Property_Update, [$c->get(PropertyController::class), 'update']);
    $r->map(RouteName::Api_Property_Delete, [$c->get(PropertyController::class), 'delete']);

    $r->map(RouteName::Api_Organization_Create, [$c->get(OrganizationController::class), 'create']);
    $r->map(RouteName::Api_Organization_Update, [$c->get(OrganizationController::class), 'update']);
    $r->map(RouteName::Api_Organization_Delete, [$c->get(OrganizationController::class), 'delete']);
};
