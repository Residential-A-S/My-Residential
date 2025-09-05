<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Exceptions\PropertyException;
use src\Exceptions\ResponseException;
use src\Exceptions\ServerException;
use src\Exceptions\ValidationException;
use src\Factories\FormFactory;
use src\Forms\CreatePropertyForm;
use src\Forms\DeletePropertyForm;
use src\Forms\UpdatePropertyForm;
use src\Services\AuthService;
use src\Services\PropertyService;

final readonly class PropertyController
{
    public function __construct(
        private PropertyService $propertyService,
        private AuthService $authService,
        private FormFactory $formFactory,
    ) {
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws PropertyException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function create(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleCreatePropertyForm($request->parsedBody);

        $this->propertyService->create(
            $form->organizationId,
            $form->streetName,
            $form->streetNumber,
            $form->zipCode,
            $form->city,
            $form->country
        );
        return Response::json(['message' => 'Created property successfully']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws PropertyException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function update(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleUpdatePropertyForm($request->parsedBody);

        $this->propertyService->update(
            $form->propertyId,
            $form->streetName,
            $form->streetNumber,
            $form->zipCode,
            $form->city,
            $form->country
        );
        return Response::json(['message' => 'Updated property successfully']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws PropertyException
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function delete(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleDeletePropertyForm($request->parsedBody);

        $this->propertyService->delete($form->propertyId);
        return Response::json(['message' => 'Property deleted successfully']);
    }
}
