<?php

namespace src\Providers;

use PDO;
use src\Core\Container;
use src\Factories\IssueFactory;
use src\Factories\OrganizationFactory;
use src\Factories\PaymentFactory;
use src\Factories\PropertyFactory;
use src\Factories\RentalAgreementDocumentFactory;
use src\Factories\RentalAgreementFactory;
use src\Factories\RentalUnitFactory;
use src\Factories\TenantFactory;
use src\Factories\UserFactory;
use src\Repositories\IssueRepository;
use src\Repositories\OrganizationRepository;
use src\Repositories\PasswordResetRepository;
use src\Repositories\PaymentRepository;
use src\Repositories\PropertyRepository;
use src\Repositories\RentalAgreementDocumentRepository;
use src\Repositories\RentalAgreementPaymentRepository;
use src\Repositories\RentalAgreementRepository;
use src\Repositories\RentalUnitRepository;
use src\Repositories\TenantRepository;
use src\Repositories\UserOrganizationRepository;
use src\Repositories\UserRepository;
use src\Services\AuthService;
use src\Services\IssueService;
use src\Services\MailService;
use src\Services\OrganizationService;
use src\Services\PasswordResetService;
use src\Services\PaymentService;
use src\Services\PropertyService;
use src\Services\RentalAgreementService;
use src\Services\RentalUnitService;
use src\Services\TenantService;
use src\Services\TranslatorService;
use src\Services\UserOrganizationService;
use src\Services\UserService;
use Twig\Environment;

final readonly class ServiceProvider implements ProviderInterface
{
    public function __construct(
        private array $config,
    ) {
    }

    public function register(Container $c): void
    {
        $c->bind(AuthService::class, fn($c) => new AuthService(
            $this->config['session'],
            $c->get(UserRepository::class),
            $c->get(UserOrganizationRepository::class)
        ));

        $c->bind(IssueService::class, fn($c) => new IssueService(
            $c->get(IssueRepository::class),
            $c->get(AuthService::class)
        ));

        $c->bind(MailService::class, fn($c) => new MailService(
            $c->get(Environment::class)
        ));

        $c->bind(UserOrganizationService::class, fn($c) => new UserOrganizationService(
            $c->get(UserOrganizationRepository::class),
            $c->get(UserRepository::class),
            $c->get(OrganizationRepository::class),
            $c->get(AuthService::class),
            $c->get(PDO::class)
        ));

        $c->bind(OrganizationService::class, fn($c) => new OrganizationService(
            $c->get(OrganizationRepository::class),
            $c->get(AuthService::class),
            $c->get(UserOrganizationService::class),
            $c->get(PDO::class)
        ));

        $c->bind(PasswordResetService::class, fn($c) => new PasswordResetService(
            $c->get(UserRepository::class),
            $c->get(PasswordResetRepository::class),
            $c->get(UserFactory::class),
            $c->get(MailService::class)
        ));

        $c->bind(PaymentService::class, fn($c) => new PaymentService(
            $c->get(PaymentRepository::class),
            $c->get(AuthService::class)
        ));

        $c->bind(PropertyService::class, fn($c) => new PropertyService(
            $c->get(PropertyRepository::class),
            $c->get(AuthService::class)
        ));

        $c->bind(RentalAgreementService::class, fn($c) => new RentalAgreementService(
            $c->get(RentalAgreementRepository::class),
            $c->get(AuthService::class)
        ));

        $c->bind(RentalUnitService::class, fn($c) => new RentalUnitService(
            $c->get(RentalUnitRepository::class),
            $c->get(AuthService::class)
        ));

        $c->bind(TenantService::class, fn($c) => new TenantService(
            $c->get(TenantRepository::class),
            $c->get(AuthService::class)
        ));

        //$c->bind(TranslatorService::class, fn($c) => new TranslatorService());

        $c->bind(UserService::class, fn($c) => new UserService(
            $c->get(AuthService::class),
            $c->get(UserRepository::class),
            $c->get(UserFactory::class)
        ));
    }
}
