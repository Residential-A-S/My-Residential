<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\Types\IssueStatus;
use Domain\ValueObject\IssueId;
use Domain\ValueObject\PaymentId;
use Domain\ValueObject\RentalAgreementId;

final readonly class Issue
{
    public function __construct(
        public IssueId $id,
        public RentalAgreementId $rentalAgreementId,
        public ?PaymentId $paymentId,
        public string $name,
        public string $description,
        public IssueStatus $status,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }

    public function changeStatus(IssueStatus $newStatus): self
    {
        return new self(
            id: $this->id,
            rentalAgreementId: $this->rentalAgreementId,
            paymentId: $this->paymentId,
            name: $this->name,
            description: $this->description,
            status: $newStatus,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function reassignPayment(PaymentId $newPaymentId): self
    {
        return new self(
            id: $this->id,
            rentalAgreementId: $this->rentalAgreementId,
            paymentId: $newPaymentId,
            name: $this->name,
            description: $this->description,
            status: $this->status,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function removePayment(): self
    {
        return new self(
            id: $this->id,
            rentalAgreementId: $this->rentalAgreementId,
            paymentId: null,
            name: $this->name,
            description: $this->description,
            status: $this->status,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function rename(string $newName): self
    {
        return new self(
            id: $this->id,
            rentalAgreementId: $this->rentalAgreementId,
            paymentId: $this->paymentId,
            name: $newName,
            description: $this->description,
            status: $this->status,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function updateDescription(string $newDescription): self
    {
        return new self(
            id: $this->id,
            rentalAgreementId: $this->rentalAgreementId,
            paymentId: $this->paymentId,
            name: $this->name,
            description: $newDescription,
            status: $this->status,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }
}
