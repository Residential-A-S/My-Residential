<?php

namespace Adapter\Provider;

use Adapter\Bootstrap\Container;
use Adapter\Http\Form\FormFactory;
use Domain\Factory\IssueFactory;
use Domain\Factory\OrganizationFactory;
use Domain\Factory\PaymentFactory;
use Domain\Factory\PropertyFactory;
use Domain\Factory\RentalAgreementDocumentFactory;
use Domain\Factory\RentalAgreementFactory;
use Domain\Factory\RentalAgreementPaymentFactory;
use Domain\Factory\RentalUnitFactory;
use Domain\Factory\TenantFactory;
use Domain\Factory\UserFactory;

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
