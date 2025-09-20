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
use Adapter\Persistence\PaymentRepository;
use Adapter\Persistence\PropertyRepository;
use Adapter\Persistence\RentalAgreementDocumentRepository;
use Adapter\Persistence\RentalAgreementPaymentRepository;
use Adapter\Persistence\RentalAgreementRepository;
use Adapter\Persistence\RentalUnitRepository;
use Adapter\Persistence\TenantRepository;
use Adapter\Persistence\UserOrganizationRepository;
use Adapter\Persistence\UserRepository;

final readonly class RepositoryProvider implements ProviderInterface
{
    public function register(Container $c): void
    {
        $c->bind(IssueRepository::class, fn($c) => new IssueRepository(
            $c->get(PDO::class),
            $c->get(IssueFactory::class)
        ));

        $c->bind(OrganizationRepository::class, fn($c) => new OrganizationRepository(
            $c->get(PDO::class),
            $c->get(OrganizationFactory::class)
        ));

        $c->bind(PaymentRepository::class, fn($c) => new PaymentRepository(
            $c->get(PDO::class),
            $c->get(PaymentFactory::class)
        ));

        $c->bind(PropertyRepository::class, fn($c) => new PropertyRepository(
            $c->get(PDO::class),
            $c->get(PropertyFactory::class)
        ));

        $c->bind(RentalAgreementRepository::class, fn($c) => new RentalAgreementRepository(
            $c->get(PDO::class),
            $c->get(RentalAgreementFactory::class)
        ));

        $c->bind(RentalAgreementDocumentRepository::class, fn($c) => new RentalAgreementDocumentRepository(
            $c->get(PDO::class),
            $c->get(RentalAgreementDocumentFactory::class)
        ));

        $c->bind(RentalAgreementPaymentRepository::class, fn($c) => new RentalAgreementPaymentRepository(
            $c->get(PDO::class)
        ));

        $c->bind(RentalUnitRepository::class, fn($c) => new RentalUnitRepository(
            $c->get(PDO::class),
            $c->get(RentalUnitFactory::class)
        ));

        $c->bind(TenantRepository::class, fn($c) => new TenantRepository(
            $c->get(PDO::class),
            $c->get(TenantFactory::class)
        ));

        $c->bind(UserOrganizationRepository::class, fn($c) => new UserOrganizationRepository(
            $c->get(PDO::class)
        ));

        $c->bind(UserRepository::class, fn($c) => new UserRepository(
            $c->get(PDO::class),
            $c->get(UserFactory::class)
        ));
    }
}
