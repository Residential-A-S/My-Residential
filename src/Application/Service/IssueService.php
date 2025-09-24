<?php

namespace Application\Service;

use Adapter\Dto\Command\IssueCreateCommand;
use DateTimeImmutable;
use Application\Exception\AuthenticationException;
use Domain\Exception\IssueException;
use Shared\Exception\ServerException;
use src\Entity\Issue;
use Adapter\Persistence\PdoIssueRepository;
use Application\Service\AuthenticationService;

final readonly class IssueService
{
    public function __construct(
        private PdoIssueRepository $issueR,
        private AuthenticationService $authS,
    ) {
    }

    /**
     * @throws AuthenticationException
     * @throws IssueException
     * @throws ServerException
     */
    public function create(IssueCreateCommand $cmd): void {
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
