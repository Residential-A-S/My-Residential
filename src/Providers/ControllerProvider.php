<?php

namespace src\Providers;

use src\Controllers\Api\AuthController;
use src\Controllers\Api\IssueController;
use src\Controllers\Api\OrganizationController;
use src\Controllers\Api\PasswordResetController;
use src\Controllers\Api\PaymentController;
use src\Controllers\Api\PropertyController;
use src\Controllers\Api\UserController;
use src\Core\Container;
use src\Factories\FormFactory;
use src\Services\AuthService;
use src\Services\IssueService;
use src\Services\OrganizationService;
use src\Services\PasswordResetService;
use src\Services\PaymentService;
use src\Services\PropertyService;
use src\Services\UserService;

final readonly class ControllerProvider implements ProviderInterface
{
    public function register(Container $c): void
    {
        $c->bind(AuthController::class, fn($c) => new AuthController(
            $c->get(AuthService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(IssueController::class, fn($c) => new IssueController(
            $c->get(IssueService::class),
            $c->get(AuthService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(OrganizationController::class, fn($c) => new OrganizationController(
            $c->get(OrganizationService::class),
            $c->get(AuthService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(PasswordResetController::class, fn($c) => new PasswordResetController(
            $c->get(PasswordResetService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(PaymentService::class, fn($c) => new PaymentController(
            $c->get(PaymentService::class),
            $c->get(AuthService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(PropertyController::class, fn($c) => new PropertyController(
            $c->get(PropertyService::class),
            $c->get(AuthService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(UserController::class, fn($c) => new UserController(
            $c->get(UserService::class),
            $c->get(AuthService::class),
            $c->get(FormFactory::class)
        ));
    }
}
