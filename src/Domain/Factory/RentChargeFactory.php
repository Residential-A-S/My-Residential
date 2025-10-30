<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\RentCharge;
use Domain\ValueObject\PaymentId;
use Domain\ValueObject\RentalAgreementId;

final readonly class RentChargeFactory
{
    public function __construct(
        private UlidFactory $ulidFactory
    ) {
    }

    public function create(
        RentalAgreementId $rentalAgreementId,
        PaymentId $paymentId,
        DateTimeImmutable $periodStart,
        DateTimeImmutable $periodEnd
    ): RentCharge
    {
        return new RentCharge(
            id: $this->ulidFactory->rentChargeId(),
            rentalAgreementId: $rentalAgreementId,
            paymentId: $paymentId,
            periodStart: $periodStart,
            periodEnd: $periodEnd
        );
    }
}
