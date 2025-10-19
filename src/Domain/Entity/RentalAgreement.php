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

    public function changeStatus(RentalAgreementStatus $newStatus): self
    {
        return new self(
            id: $this->id,
            rentalUnitId: $this->rentalUnitId,
            startDate: $this->startDate,
            endDate: $this->endDate,
            status: $newStatus,
            paymentInterval: $this->paymentInterval,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function extendEndDate(DateTimeImmutable $newEndDate): self
    {
        return new self(
            id: $this->id,
            rentalUnitId: $this->rentalUnitId,
            startDate: $this->startDate,
            endDate: $newEndDate,
            status: $this->status,
            paymentInterval: $this->paymentInterval,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function changePaymentInterval(PaymentInterval $newInterval): self
    {
        return new self(
            id: $this->id,
            rentalUnitId: $this->rentalUnitId,
            startDate: $this->startDate,
            endDate: $this->endDate,
            status: $this->status,
            paymentInterval: $newInterval,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function isActive(): bool
    {
        return $this->status === RentalAgreementStatus::ACTIVE;
    }
}
