<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Domain\Exception\IssueException;
use Adapter\Http\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
use Domain\Factory\FormFactory;
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

        return Response::json(['message' => 'CreateIssueCommand creation successful.']);
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
        return Response::json(['message' => 'CreateIssueCommand update successful.']);
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
        return Response::json(['message' => 'CreateIssueCommand deleted successfully']);
    }
}