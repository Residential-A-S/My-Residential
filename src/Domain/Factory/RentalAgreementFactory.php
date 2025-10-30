<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\RentalAgreement;
use Domain\Types\PaymentInterval;
use Domain\Types\RentalAgreementStatus;
use Domain\ValueObject\RentalUnitId;

final readonly class RentalAgreementFactory
{
    public function __construct(
        private UlidFactory $ulidFactory
    ) {
    }

    public function create(
        RentalUnitId $rentalUnitId,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        RentalAgreementStatus $status,
        PaymentInterval $paymentInterval
    ): RentalAgreement
    {
        $now = new DateTimeImmutable();

        return new RentalAgreement(
            id: $this->ulidFactory->rentalAgreementId(),
            rentalUnitId: $rentalUnitId,
            startDate: $startDate,
            endDate: $endDate,
            status: $status,
            paymentInterval: $paymentInterval,
            createdAt: $now,
            updatedAt: $now
        );
    }
}
