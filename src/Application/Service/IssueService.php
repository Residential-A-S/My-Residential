<?php

namespace Application\Service;
use Application\Dto\Command\IssueCreateCommand;
use Application\Exception\AuthenticationException;
use Application\Port\IssueRepository;
use DateTimeImmutable;
use Domain\Factory\IssueFactory;
use Domain\Types\IssueStatus;
use Domain\ValueObject\RentalAgreementId;

final readonly class IssueService
{
    public function __construct(
        private IssueRepository $issueRepository,
        private AuthenticationService $authenticationService,
        private IssueFactory $issueFactory
    ) {
    }

    /**
     * @throws AuthenticationException
     */
    public function create(IssueCreateCommand $cmd): void {
        $this->authenticationService->requireUser();
        $issue = $this->issueFactory->create(
            rentalAgreementId: new RentalAgreementId($cmd->rentalAgreementId),
            paymentId: $cmd->paymentId,
            name: $cmd->name,
            description: $cmd->description,
            status: IssueStatus::from($cmd->status)
        );
        $this->issueRepository->save($issue);
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
