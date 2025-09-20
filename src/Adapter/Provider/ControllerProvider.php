<?php

namespace src\Providers;

use Adapter\Http\Controllers\Api\AuthController;
use Adapter\Http\Controllers\Api\IssueController;
use Adapter\Http\Controllers\Api\OrganizationController;
use Adapter\Http\Controllers\Api\PasswordResetController;
use Adapter\Http\Controllers\Api\PaymentController;
use Adapter\Http\Controllers\Api\PropertyController;
use Adapter\Http\Controllers\Api\UserController;
use src\Core\Container;
use src\Factories\FormFactory;
use Application\Service\AuthenticationService;
use Application\Service\IssueService;
use Application\Service\OrganizationService;
use Application\Service\PasswordResetService;
use Application\Service\PaymentService;
use Application\Service\PropertyService;
use Application\Service\UserService;

final readonly class ControllerProvider implements ProviderInterface
{
    public function register(Container $c): void
    {
        $c->bind(AuthController::class, fn($c) => new AuthController(
            $c->get(AuthenticationService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(IssueController::class, fn($c) => new IssueController(
            $c->get(IssueService::class),
            $c->get(AuthenticationService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(OrganizationController::class, fn($c) => new OrganizationController(
            $c->get(OrganizationService::class),
            $c->get(AuthenticationService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(PasswordResetController::class, fn($c) => new PasswordResetController(
            $c->get(PasswordResetService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(PaymentService::class, fn($c) => new PaymentController(
            $c->get(PaymentService::class),
            $c->get(AuthenticationService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(PropertyController::class, fn($c) => new PropertyController(
            $c->get(PropertyService::class),
            $c->get(AuthenticationService::class),
            $c->get(FormFactory::class)
        ));

        $c->bind(UserController::class, fn($c) => new UserController(
            $c->get(UserService::class),
            $c->get(AuthenticationService::class),
            $c->get(FormFactory::class)
        ));
    }
}
