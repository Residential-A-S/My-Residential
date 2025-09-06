<?php

namespace src\Providers;

use src\Core\Container;
use src\Factories\FormFactory;
use src\Factories\IssueFactory;
use src\Factories\OrganizationFactory;
use src\Factories\PaymentFactory;
use src\Factories\PropertyFactory;
use src\Factories\RentalAgreementDocumentFactory;
use src\Factories\RentalAgreementFactory;
use src\Factories\RentalAgreementPaymentFactory;
use src\Factories\RentalUnitFactory;
use src\Factories\TenantFactory;
use src\Factories\UserFactory;

final readonly class FactoryProvider implements ProviderInterface
{
    public function register(Container $c): void
    {
        $c->bind(IssueFactory::class, fn($c) => new IssueFactory());
        $c->bind(OrganizationFactory::class, fn($c) => new OrganizationFactory());
        $c->bind(PaymentFactory::class, fn($c) => new PaymentFactory());
        $c->bind(PropertyFactory::class, fn($c) => new PropertyFactory());
        $c->bind(RentalAgreementFactory::class, fn($c) => new RentalAgreementFactory());
        $c->bind(RentalAgreementDocumentFactory::class, fn($c) => new RentalAgreementDocumentFactory());
        $c->bind(RentalAgreementPaymentFactory::class, fn($c) => new RentalAgreementPaymentFactory());
        $c->bind(RentalUnitFactory::class, fn($c) => new RentalUnitFactory());
        $c->bind(TenantFactory::class, fn($c) => new TenantFactory());
        $c->bind(UserFactory::class, fn($c) => new UserFactory());
        $c->bind(FormFactory::class, fn($c) => new FormFactory());
    }
}
