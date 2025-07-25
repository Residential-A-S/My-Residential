<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Services\AuthService;
use src\Services\PropertyService;

final readonly class PropertyController
{
    public function __construct(
        private PropertyService $propertyService,
        private AuthService $authService,
    ) {}

    public function createProperty(Request $request): Response
    {
        $this->authService->requireUser();

        $createPropertyForm = new CreatePropertyForm();
        $createPropertyForm->handle($request->body);

        $this->propertyService->create(
            $createPropertyForm->data['country'],
            $createPropertyForm->data['postal_code'],
            $createPropertyForm->data['city'],
            $createPropertyForm->data['address']
        );
        return Response::json(['message' => 'success']);
    }

    public function updateProperty(Request $request): Response
    {
        $this->authService->requireUser();

        $updatePropertyForm = new UpdatePropertyForm();
        $updatePropertyForm->handle($request->body);

        $this->propertyService->update($updatePropertyForm->data);
        return Response::json(['message' => 'success']);
    }
}