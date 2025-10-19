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

    public function changePeriod(DateTimeImmutable $newStart, DateTimeImmutable $newEnd): self
    {
        return new self(
            id: $this->id,
            rentalAgreementId: $this->rentalAgreementId,
            paymentId: $this->paymentId,
            periodStart: $newStart,
            periodEnd: $newEnd,
        );
    }

    public function reassignPayment(PaymentId $newPaymentId): self
    {
        return new self(
            id: $this->id,
            rentalAgreementId: $this->rentalAgreementId,
            paymentId: $newPaymentId,
            periodStart: $this->periodStart,
            periodEnd: $this->periodEnd,
        );
    }

    public function getPeriodDurationInDays(): int
    {
        return $this->periodStart->diff($this->periodEnd)->days;
    }
}
