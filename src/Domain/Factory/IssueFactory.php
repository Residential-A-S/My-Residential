<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\Issue;
use Domain\Types\IssueStatus;
use Domain\ValueObject\PaymentId;
use Domain\ValueObject\RentalAgreementId;
use Shared\Factory\UlidFactory;

final readonly class IssueFactory
{
    public function __construct(
        private UlidFactory $ulidFactory,
    ) {
    }
    public function create(
        RentalAgreementId $rentalAgreementId,
        ?PaymentId $paymentId,
        string $name,
        string $description,
        IssueStatus $status
    ): Issue
    {
        $now = new DateTimeImmutable();
        return new Issue(
            id: $this->ulidFactory->issueId(),
            rentalAgreementId: $rentalAgreementId,
            paymentId: $paymentId,
            name: $name,
            description: $description,
            status: $status,
            createdAt: $now,
            updatedAt: $now
        );
    }
}
