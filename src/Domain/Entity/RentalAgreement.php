<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\RentalAgreementId;
use Domain\ValueObject\RentalUnitId;
use Domain\Types\{PaymentInterval, RentalAgreementStatus};

final readonly class RentalAgreement
{
    public function __construct(
        public RentalAgreementId $id,
        public RentalUnitId $rentalUnitId,
        public DateTimeImmutable $startDate,
        public ?DateTimeImmutable $endDate,
        public RentalAgreementStatus $status,
        public PaymentInterval $paymentInterval,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }
}
