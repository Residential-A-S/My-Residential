<?php

namespace src\Factories;

use src\Models\Issue;

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
}
