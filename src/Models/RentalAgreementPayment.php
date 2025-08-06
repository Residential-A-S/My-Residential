<?php

namespace src\Models;

use DateTimeImmutable;

final readonly class RentalAgreementPayment
{
    public function __construct(
        public int $rentalAgreementId,
        public int $paymentId,
        public DateTimeImmutable $periodStart,
        public DateTimeImmutable $periodEnd,
    ) {
    }
}
