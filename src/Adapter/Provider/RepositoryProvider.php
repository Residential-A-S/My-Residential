<?php

namespace Adapter\Provider;

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
use Domain\Factory\UserFactory;
use Adapter\Persistence\PdoIssueRepository;
use Adapter\Persistence\PdoOrganizationRepository;
use Adapter\Persistence\PdoPaymentRepository;
use Adapter\Persistence\PdoPropertyRepository;
use Adapter\Persistence\PdoRentalAgreementDocumentRepository;
use Adapter\Persistence\PdoRentChargeRepository;
use Adapter\Persistence\PdoRentalAgreementRepository;
use Adapter\Persistence\PdoRentalUnitRepository;
use Adapter\Persistence\PdoTenantRepository;
use Adapter\Persistence\PdoUserOrganizationRepository;
use Adapter\Persistence\PdoUserRepository;

final readonly class RepositoryProvider implements ProviderInterface
{
    public function register(Container $c): void
    {
        $c->bind(PdoIssueRepository::class, fn($c) => new PdoIssueRepository(
            $c->get(PDO::class),
            $c->get(IssueFactory::class)
        ));

        $c->bind(PdoOrganizationRepository::class, fn($c) => new PdoOrganizationRepository(
            $c->get(PDO::class),
            $c->get(OrganizationFactory::class)
        ));

        $c->bind(PdoPaymentRepository::class, fn($c) => new PdoPaymentRepository(
            $c->get(PDO::class),
            $c->get(PaymentFactory::class)
        ));

        $c->bind(PdoPropertyRepository::class, fn($c) => new PdoPropertyRepository(
            $c->get(PDO::class),
            $c->get(PropertyFactory::class)
        ));

        $c->bind(PdoRentalAgreementRepository::class, fn($c) => new PdoRentalAgreementRepository(
            $c->get(PDO::class),
            $c->get(RentalAgreementFactory::class)
        ));

        $c->bind(PdoRentalAgreementDocumentRepository::class, fn($c) => new PdoRentalAgreementDocumentRepository(
            $c->get(PDO::class),
            $c->get(RentalAgreementDocumentFactory::class)
        ));

        $c->bind(PdoRentChargeRepository::class, fn($c) => new PdoRentChargeRepository(
            $c->get(PDO::class)
        ));

        $c->bind(PdoRentalUnitRepository::class, fn($c) => new PdoRentalUnitRepository(
            $c->get(PDO::class),
            $c->get(RentalUnitFactory::class)
        ));

        $c->bind(PdoTenantRepository::class, fn($c) => new PdoTenantRepository(
            $c->get(PDO::class),
            $c->get(TenantFactory::class)
        ));

        $c->bind(PdoUserOrganizationRepository::class, fn($c) => new PdoUserOrganizationRepository(
            $c->get(PDO::class)
        ));

        $c->bind(PdoUserRepository::class, fn($c) => new PdoUserRepository(
            $c->get(PDO::class),
            $c->get(UserFactory::class)
        ));
    }
}
   