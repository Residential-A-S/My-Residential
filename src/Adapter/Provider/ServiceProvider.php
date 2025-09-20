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
use Adapter\Persistence\IssueRepository;
use Adapter\Persistence\OrganizationRepository;
use Adapter\Persistence\PasswordResetRepository;
use Adapter\Persistence\PaymentRepository;
use Adapter\Persistence\PropertyRepository;
use Adapter\Persistence\RentalAgreementDocumentRepository;
use Adapter\Persistence\RentalAgreementPaymentRepository;
use Adapter\Persistence\RentalAgreementRepository;
use Adapter\Persistence\RentalUnitRepository;
use Adapter\Persistence\TenantRepository;
use Adapter\Persistence\UserOrganizationRepository;
use Adapter\Persistence\UserRepository;
use Application\Service\AuthenticationService;
use Application\Service\IssueService;
use Adapter\Mail\Mailer;
use Application\Service\OrganizationService;
use Application\Service\PasswordResetService;
use Application\Service\PaymentService;
use Application\Service\PropertyService;
use Application\Service\RentalAgreementService;
use Application\Service\RentalUnitService;
use Application\Service\TenantService;
use Adapter\I18n\Translator;
use Application\Service\UserOrganizationService;
use Application\Service\UserService;
use Twig\Environment;

final readonly class ServiceProvider implements ProviderInterface
{
    public function __construct(
        private array $config,
    ) {
    }

    public function register(Container $c): void
    {
        $c->bind(AuthenticationService::class, fn($c) => new AuthenticationService(
            $this->config['session'],
            $c->get(UserRepository::class),
            $c->get(UserOrganizationRepository::class)
        ));

        $c->bind(IssueService::class, fn($c) => new IssueService(
            $c->get(IssueRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(Mailer::class, fn($c) => new Mailer(
            $c->get(Environment::class)
        ));

        $c->bind(UserOrganizationService::class, fn($c) => new UserOrganizationService(
            $c->get(UserOrganizationRepository::class),
            $c->get(UserRepository::class),
            $c->get(OrganizationRepository::class),
            $c->get(AuthenticationService::class),
            $c->get(PDO::class)
        ));

        $c->bind(OrganizationService::class, fn($c) => new OrganizationService(
            $c->get(OrganizationRepository::class),
            $c->get(AuthenticationService::class),
            $c->get(UserOrganizationService::class),
            $c->get(PDO::class)
        ));

        $c->bind(PasswordResetService::class, fn($c) => new PasswordResetService(
            $c->get(UserRepository::class),
            $c->get(PasswordResetRepository::class),
            $c->get(UserFactory::class),
            $c->get(Mailer::class)
        ));

        $c->bind(PaymentService::class, fn($c) => new PaymentService(
            $c->get(PaymentRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(PropertyService::class, fn($c) => new PropertyService(
            $c->get(PropertyRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(RentalAgreementService::class, fn($c) => new RentalAgreementService(
            $c->get(RentalAgreementRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(RentalUnitService::class, fn($c) => new RentalUnitService(
            $c->get(RentalUnitRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(TenantService::class, fn($c) => new TenantService(
            $c->get(TenantRepository::class),
            $c->get(AuthenticationService::class)
        ));

        //$c->bind(Translator::class, fn($c) => new Translator());

        $c->bind(UserService::class, fn($c) => new UserService(
            $c->get(Authenti