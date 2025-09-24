<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\Types\IssueStatus;
use Domain\ValueObject\IssueId;
use Domain\ValueObject\RentalAgreementId;

final readonly class Issue
{
    public function __construct(
        public IssueId $id,
        public RentalAgreementId $rentalAgreementId,
        public ?int $paymentId,
        public string $name,
        public string $description,
        public IssueStatus $status,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }
}
