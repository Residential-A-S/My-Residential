<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Domain\Exception\PropertyException;
use Adapter\Http\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
use Domain\Factory\FormFactory;
use src\Forms\CreatePropertyForm;
use src\Forms\DeletePropertyForm;
use src\Forms\UpdatePropertyForm;
use Application\Service\AuthenticationService;
use Application\Service\PropertyService;

final readonly class PropertyController
{
    public function __construct(
        private PropertyService $propertyService,
        private AuthenticationService $authService,
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