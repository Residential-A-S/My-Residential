<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Http\Form\FormFactory;
use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Domain\Exception\IssueException;
use Adapter\Http\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
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
        $cmd = $this->formFactory->handleCreateIssueForm($request->parsedBody)->command;

        $this->issueService->create($cmd);

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
        $cmd = $this->formFactory->handleUpdateIssueForm($request->parsedBody)->command;

        $this->issueService->update($cmd);
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
        $cmd = $this->formFactory->handleDeleteIssueForm($request->parsedBody)->command;

        $this->issueService->delete($cmd);
        return Response::json(['message' => 'CreateIssueCommand deleted successfully']);
    }
}