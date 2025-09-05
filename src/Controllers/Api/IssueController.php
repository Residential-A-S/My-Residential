<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Exceptions\IssueException;
use src\Exceptions\ResponseException;
use src\Exceptions\ServerException;
use src\Services\AuthService;
use src\Services\IssueService;

final readonly class IssueController
{
    public function __construct(
        private IssueService $issueService,
        private AuthService $authService,
    ) {
    }

    /**
     * @throws IssueException
     * @throws ResponseException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function create(Request $request): Response
    {
        $this->authService->requireUser();
        $createIssueForm = new CreateIssueForm();
        $createIssueForm->handle($request->parsedBody);

        $this->issueService->create(
            $createIssueForm->data['rental_agreement_id'],
            $createIssueForm->data['payment_id'],
            $createIssueForm->data['name'],
            $createIssueForm->data['description'],
            $createIssueForm->data['status']
        );

        return Response::json(['message' => 'Issue creation successful.']);
    }

    /**
     * @throws ResponseException
     * @throws IssueException
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function update(Request $request): Response
    {
        $this->authService->requireUser();
        $updateIssueForm = new UpdateIssueForm();
        $updateIssueForm->handle($request->parsedBody);

        $this->issueService->update(
            $updateIssueForm->data['id'],
            $updateIssueForm->data['rental_agreement_id'],
            $updateIssueForm->data['payment_id'],
            $updateIssueForm->data['name'],
            $updateIssueForm->data['description'],
            $updateIssueForm->data['status']
        );
        return Response::json(['message' => 'Issue update successful.']);
    }

    /**
     * @throws IssueException
     * @throws ResponseException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function delete(Request $request): Response
    {
        $this->authService->requireUser();
        $deletePropertyForm = new DeleteIssueForm();
        $deletePropertyForm->handle($request->parsedBody);

        $this->issueService->delete($deletePropertyForm->data['id']);
        return Response::json(['message' => 'Issue deleted successfully']);
    }
}
