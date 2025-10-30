<?php

namespace Adapter\Provider;

use Adapter\Bootstrap\Container;
use Adapter\Persistence\Pdo\IssueRepository;
use Adapter\Persistence\Pdo\OrganizationRepository;
use Adapter\Persistence\Pdo\PdoPaymentRepository;
use Adapter\Persistence\Pdo\PropertyRepository;
use Adapter\Persistence\Pdo\RentalAgreementRepository;
use Adapter\Persistence\Pdo\RentalUnitRepository;
use Adapter\Persistence\Pdo\RentChargeRepository;
use Adapter\Persistence\Pdo\TenantRepository;
use Adapter\Persistence\Pdo\UserOrganizationRepository;
use Adapter\Persistence\Pdo\UserRepository;
use Adapter\Persistence\PdoRentalAgreementDocumentRepository;
use Domain\Factory\IssueFactory;
use Domain\Factory\OrganizationFactory;
use Domain\Factory\PaymentFactory;
use Domain\Factory\PropertyFactory;
use Domain\Factory\RentalAgreementDocumentFactory;
use Domain\Factory\RentalAgreementFactory;
use Domain\Factory\RentalUnitFactory;
use Domain\Factory\TenantFactory;
use Domain\Factory\UserFactory;
use PDO;

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

        $c->bind(PdoPaymentRepository::class, fn($c) => new PdoPaymentRepository(
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

        $c->bind(PdoRentalAgreementDocumentRepository::class, fn($c) => new PdoRentalAgreementDocumentRepository(
            $c->get(PDO::class),
            $c->get(RentalAgreementDocumentFactory::class)
        ));

        $c->bind(RentChargeRepository::class, fn($c) => new RentChargeRepository(
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
   