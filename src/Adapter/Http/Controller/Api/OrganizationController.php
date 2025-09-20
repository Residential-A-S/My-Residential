<?php

namespace Adapter\Http\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use Application\Exception\AuthenticationException;
use Shared\Exception\BaseException;
use Domain\Exception\OrganizationException;
use src\Core\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
use src\Factories\FormFactory;
use src\Forms\CreateOrganizationForm;
use src\Forms\DeleteOrganizationForm;
use src\Forms\UpdateOrganizationForm;
use Application\Service\AuthenticationService;
use Application\Service\OrganizationService;

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
        $form = $this->formFactory->handleCreateOrganizationForm($request->parsedBody);

        $this->orgService->create($form->name);
        return Response::json(['message' => 'Organization created successfully']);
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
        $form = $this->formFactory->handleUpdateOrganizationForm($request->parsedBody);

        $this->orgService->update(
            $form->organizationId,
            $form->name,
        );
        return Response::json(['message' => 'Organization updated successfully']);
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
        $form = $this->formFactory->handleDeleteOrganizationForm($request->parsedBody);

        $this->orgService->delete($form->organizationId);
        return Response::json(['message' => 'Organization deleted successfully']);
    }
}
