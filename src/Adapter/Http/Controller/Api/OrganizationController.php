<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Exception\ResponseException;
use Adapter\Exception\ValidationException;
use Adapter\Http\Form\FormFactory;
use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Application\Service\AuthenticationService;
use Application\Service\OrganizationService;
use Domain\Exception\OrganizationException;
use Shared\Exception\BaseException;
use Shared\Exception\ServerException;

final readonly class OrganizationController
{
    public function __construct(
        private OrganizationService $orgService,
        private AuthenticationService $authService,
        private FormFactory $formFactory,
    ) {
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws AuthenticationException
     * @throws OrganizationException
     * @throws ResponseException
     * @throws ServerException
     * @throws ValidationException
     * @throws BaseException
     */
    public function create(Request $request): Response
    {
        $this->authService->requireUser();
        $cmd = $this->formFactory->handleCreateOrganizationForm($request->parsedBody)->command;

        $this->orgService->create($cmd);
        return Response::json(['message' => 'CreateOrganizationCommand created successfully']);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws AuthenticationException
     * @throws ResponseException
     * @throws ValidationException
     * @throws OrganizationException
     * @throws ServerException
     */
    public function update(Request $request): Response
    {
        $this->authService->requireUser();
        $cmd = $this->formFactory->handleUpdateOrganizationForm($request->parsedBody)->command;

        $this->orgService->update($cmd);
        return Response::json(['message' => 'CreateOrganizationCommand updated successfully']);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws AuthenticationException
     * @throws OrganizationException
     * @throws ResponseException
     * @throws ServerException
     * @throws ValidationException
     */
    public function delete(Request $request): Response
    {
        $this->authService->requireUser();
        $cmd = $this->formFactory->handleDeleteOrganizationForm($request->parsedBody)->command;

        $this->orgService->delete($cmd);
        return Response::json(['message' => 'CreateOrganizationCommand deleted successfully']);
    }
}