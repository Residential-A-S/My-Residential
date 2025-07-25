<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Services\AuthService;
use src\Services\OrganizationService;

final readonly class OrganizationController
{
    public function __construct(
        private OrganizationService $orgService,
        private AuthService $authService,
    ) {}


    public function getOrganizations(Request $request): Response
    {
        $user = $this->authService->requireUser();
        $organizations = $this->orgService->getCurrentUserOrganizations($user);
        return Response::json(['data' => $organizations]);
    }

    public function createOrganization(Request $request): Response
    {
        $user = $this->authService->requireUser();

        $createOrganizationForm = new CreateOrganizationForm();
        $createOrganizationForm->handle($request->body);

        $this->orgService->createOrganization(
            $createOrganizationForm->data['name'],
            $createOrganizationForm->data['description'],
            $user
        );
        return Response::json(['message' => 'success']);
    }
}