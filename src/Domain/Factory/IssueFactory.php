<?php

namespace Domain\Factory;

use Adapter\Dto\Command\IssueCreateCommand;
use Domain\Entity\Issue;

final readonly class IssueFactory
{
    public function withId(Issue $issue, int $id): Issue
    {
        return new Issue(
            id: $id,
            rentalAgreementId: $issue->rentalAgreementId,
            paymentId: $issue->paymentId,
            name: $issue->name,
            description: $issue->description,
            status: $issue->status,
            createdAt: $issue->createdAt,
            updatedAt: $issue->updatedAt
        );
    }

    public function fromCreateCommand(IssueCreateCommand $cmd): Issue
    {
        return new Issue(
            id: null,
            rentalAgreementId: $rentalAgreementId,
            paymentId: $paymentId,
            name: $name,
            description: $description,
            status: $status,
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable()
        );
    }
}
