<?php

namespace Adapter\Provider;

use Application\Port\UserRepository;
use Domain\Factory\UserFactory;
use PDO;
use Adapter\Bootstrap\Container;
use Domain\Factory\IssueFactory;
use Domain\Factory\OrganizationFactory;
use Domain\Factory\PaymentFactory;
use Domain\Factory\PropertyFactory;
use Domain\Factory\RentalAgreementDocumentFactory;
use Domain\Factory\RentalAgreementFactory;
use Domain\Factory\RentalUnitFactory;
use Domain\Factory\TenantFactory;
use Adapter\Persistence\PdoIssueRepository;
use Adapter\Persistence\PdoOrganizationRepository;
use Adapter\Persistence\PdoPasswordResetRepository;
use Adapter\Persistence\PdoPaymentRepository;
use Adapter\Persistence\PdoPropertyRepository;
use Adapter\Persistence\PdoRentalAgreementDocumentRepository;
use Adapter\Persistence\PdoRentalAgreementPaymentRepository;
use Adapter\Persistence\PdoRentalAgreementRepository;
use Adapter\Persistence\PdoRentalUnitRepository;
use Adapter\Persistence\PdoTenantRepository;
use Adapter\Persistence\PdoUserOrganizationRepository;
use Adapter\Persistence\PdoUserRepository;
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
            $c->get(PdoUserRepository::class)
        ));

        $c->bind(IssueService::class, fn($c) => new IssueService(
            $c->get(PdoIssueRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(Mailer::class, fn($c) => new Mailer(
            $c->get(Environment::class)
        ));

        $c->bind(UserOrganizationService::class, fn($c) => new UserOrganizationService(
            $c->get(PdoUserOrganizationRepository::class),
            $c->get(PdoUserRepository::class),
            $c->get(PdoOrganizationRepository::class),
            $c->get(AuthenticationService::class),
            $c->get(PDO::class)
        ));

        $c->bind(OrganizationService::class, fn($c) => new OrganizationService(
            $c->get(PdoOrganizationRepository::class),
            $c->get(AuthenticationService::class),
            $c->get(UserOrganizationService::class),
            $c->get(PDO::class)
        ));

        $c->bind(PasswordResetService::class, fn($c) => new PasswordResetService(
            $c->get(PdoUserRepository::class),
            $c->get(PdoPasswordResetRepository::class),
            $c->get(UserFactory::class),
            $c->get(Mailer::class)
        ));

        $c->bind(PaymentService::class, fn($c) => new PaymentService(
            $c->get(PdoPaymentRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(PropertyService::class, fn($c) => new PropertyService(
            $c->get(PdoPropertyRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(RentalAgreementService::class, fn($c) => new RentalAgreementService(
            $c->get(PdoRentalAgreementRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(RentalUnitService::class, fn($c) => new RentalUnitService(
            $c->get(PdoRentalUnitRepository::class),
            $c->get(AuthenticationService::class)
        ));

        $c->bind(TenantService::class, fn($c) => new TenantService(
            $c->get(PdoTenantRepository::class),
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