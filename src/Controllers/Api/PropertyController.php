<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Exceptions\PropertyException;
use src\Exceptions\ResponseException;
use src\Exceptions\ServerException;
use src\Exceptions\ValidationException;
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
    ) {
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws PropertyException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function createProperty(Request $request): Response
    {
        $this->authService->requireUser();

        $createPropertyForm = new CreatePropertyForm();
        $createPropertyForm->handle($request->parsedBody);

        $this->propertyService->create(
            $createPropertyForm->data['org_id'],
            $createPropertyForm->data['street_name'],
            $createPropertyForm->data['street_number'],
            $createPropertyForm->data['zip_code'],
            $createPropertyForm->data['city'],
            $createPropertyForm->data['country']
        );
        return Response::json(['message' => 'success']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws PropertyException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function updateProperty(Request $request): Response
    {
        $this->authService->requireUser();

        $updatePropertyForm = new UpdatePropertyForm();
        $updatePropertyForm->handle($request->parsedBody);

        $this->propertyService->update(
            $updatePropertyForm->data['id'],
            $updatePropertyForm->data['street_name'],
            $updatePropertyForm->data['street_number'],
            $updatePropertyForm->data['zip_code'],
            $updatePropertyForm->data['city'],
            $updatePropertyForm->data['country']
        );
        return Response::json(['message' => 'success']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws PropertyException
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function deleteProperty(Request $request): Response
    {
        $this->authService->requireUser();
        $deletePropertyForm = new DeletePropertyForm();
        $deletePropertyForm->handle($request->parsedBody);

        $this->propertyService->delete($deletePropertyForm->data['id']);
        return Response::json(['message' => 'Property deleted successfully']);
    }
}
