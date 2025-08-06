<?php

namespace src\Services;

use DateTimeImmutable;
use src\Exceptions\AuthenticationException;
use src\Exceptions\IssueException;
use src\Exceptions\ServerException;
use src\Models\Issue;
use src\Repositories\IssueRepository;

final readonly class IssueService
{
    public function __construct(
        private IssueRepository $issueR,
        private AuthService $authS,
    ) {
    }

    /**
     * @param int $rentalAgreementId
     * @param int|null $paymentId
     * @param string $name
     * @param string $description
     * @param string $status
     * @throws AuthenticationException
     * @throws IssueException
     * @throws ServerException
     */
    public function create(
        int $rentalAgreementId,
        ?int $paymentId,
        string $name,
        string $description,
        string $status
    ): void {
        $this->authS->requireUser();
        $now = new DateTimeImmutable();
        $issue = new Issue(
            id: 0,
            rentalAgreementId: $rentalAgreementId,
            paymentId: $paymentId,
            name: $name,
            description: $description,
            status: $status,
            createdAt: $now,
            updatedAt: $now,
        );
        $this->issueR->create($issue);
    }

    /**
     * @throws IssueException
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function update(
        int $id,
        int $rentalAgreementId,
        ?int $paymentId,
        string $name,
        string $description,
        string $status
    ): void {
        $this->authS->requireUser();
        $issue = $this->issueR->findById($id);
        $updatedIssue = new Issue(
            id: $issue->id,
            rentalAgreementId: $rentalAgreementId,
            paymentId: $paymentId,
            name: $name,
            description: $description,
            status: $status,
            createdAt: $issue->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
        $this->issueR->update($updatedIssue);
    }

    /**
     * @throws IssueException
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function delete(int $id): void
    {
        $this->authS->requireUser();
        $issue = $this->issueR->findById($id);
        $this->issueR->delete($issue->id);
    }
}
