<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Exceptions\ResponseException;
use src\Exceptions\ValidationException;
use src\Forms\CreateOrganizationForm;
use src\Forms\DeleteOrganizationForm;
use src\Forms\UpdateOrganizationForm;
use src\Services\AuthService;
use src\Services\OrganizationService;

final readonly class OrganizationController
{
    public function __construct(
        private OrganizationService $orgService,
        private AuthService $authService,
    ) {
    }

    /**
     * @throws ResponseException
     * @throws AuthenticationException
     * @throws ValidationException
     */
    public function create(Request $request): Response
    {
        $this->authService->requireUser();

        $createOrganizationForm = new CreateOrganizationForm();
        $createOrganizationForm->handle($request->parsedBody);

        $this->orgService->create(
            $createOrganizationForm->data['name']
        );
        return Response::json(['message' => 'success']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function updateOrganization(Request $request): Response
    {
        $user = $this->authService->requireUser();

        $updateOrganizationForm = new UpdateOrganizationForm();
        $updateOrganizationForm->handle($request->parsedBody);

        $this->orgService->updateOrganization();
        return Response::json(['message' => 'success']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function deleteOrganization(Request $request): Response
    {
        $user = $this->authService->requireUser();

        $deleteOrganizationForm = new DeleteOrganizationForm();
        $deleteOrganizationForm->handle($request->parsedBody);

        $this->orgService->deleteOrganization();
        return Response::json(['message' => 'Organization deleted successfully']);
    }
}
