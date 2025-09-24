<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\PaymentId;
use Domain\ValueObject\RentalAgreementId;
use Domain\ValueObject\RentChargeId;

final readonly class RentCharge
{
    public function __construct(
        public RentChargeId $id,
        public RentalAgreementId $rentalAgreementId,
        public PaymentId $paymentId,
        public DateTimeImmutable $periodStart,
        public DateTimeImmutable $periodEnd,
    ) {
    }
}
