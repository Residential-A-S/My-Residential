<?php

namespace Adapter\Provider;

use Adapter\Bootstrap\Container;
use Adapter\Mail\Mailer;
use Adapter\Persistence\Pdo\IssueRepository;
use Adapter\Persistence\Pdo\OrganizationRepository;
use Adapter\Persistence\Pdo\PasswordResetRepository;
use Adapter\Persistence\Pdo\PdoPaymentRepository;
use Adapter\Persistence\Pdo\PropertyRepository;
use Adapter\Persistence\Pdo\RentalAgreementRepository;
use Adapter\Persistence\Pdo\RentalUnitRepository;
use Adapter\Persistence\Pdo\TenantRepository;
use Adapter\Persistence\Pdo\UserOrganizationRepository;
use Adapter\Persistence\Pdo\UserRepository;
use Adapter\Persistence\PdoRentalAgreementDocumentRepository;
use Application\Port\UserRepository;
use Application\Service\AuthenticationService;
use Application\Service\IssueService;
use Application\Service\OrganizationService;
use Application\Service\PasswordResetService;
use Application\Service\PaymentService;
use Application\Service\PropertyService;
use Application\Service\RentalAgreementService;
use Application\Service\RentalUnitService;
use Application\Service\TenantService;
use Application\Service\UserOrganizationService;
use Application\Service\UserService;
use Domain\Factory\RentalAgreementDocumentFactory;
use Domain\Factory\UserFactory;
use PDO;
use src\Providers\ProviderInterface;
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
            $c->get(UserRepository::class)
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
            $c->get(PdoPaymentRepository::class),
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
            $c->get(AuthenticationService::class),
            $c->get(UserRepository::class),
            $c->get(UserFactory::class)
        ));
    }
}