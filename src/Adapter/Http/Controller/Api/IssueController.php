<?php

namespace Adapter\Http\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use Application\Exception\AuthenticationException;
use Domain\Exception\IssueException;
use src\Core\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
use src\Factories\FormFactory;
use src\Forms\CreateIssueForm;
use src\Forms\DeleteIssueForm;
use src\Forms\UpdateIssueForm;
use Application\Service\AuthenticationService;
use Application\Service\IssueService;

final readonly class IssueController
{
    public function __construct(
        private IssueService $issueService,
        private AuthenticationService $authService,
        private FormFactory $formFactory,
    ) {
    }

    /**
     * @throws IssueException
     * @throws ResponseException
     * @throws ServerException
     * @throws AuthenticationException
     * @throws ValidationException
     */
    public function create(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleCreateIssueForm($request->parsedBody);

        $this->issueService->create(
            $form->rentalAgreementId,
            $form->paymentId,
            $form->name,
            $form->description,
            $form->status
        );

        return Response::json(['message' => 'Issue creation successful.']);
    }

    /**
     * @throws ResponseException
     * @throws IssueException
     * @throws AuthenticationException
     * @throws ServerException
     * @throws ValidationException
     */
    public function update(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleUpdateIssueForm($request->parsedBody);

        $this->issueService->update(
            $form->issueId,
            $form->rentalAgreementId,
            $form->paymentId,
            $form->name,
            $form->description,
            $form->status
        );
        return Response::json(['message' => 'Issue update successful.']);
    }

    /**
     * @throws IssueException
     * @throws ResponseException
     * @throws ServerException
     * @throws AuthenticationException
     * @throws ValidationException
     */
    public function delete(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleDeleteIssueForm($request->parsedBody);

        $this->issueService->delete($form->issueId);
        return Response::json(['message' => 'Issue deleted successfully']);
    }
}
