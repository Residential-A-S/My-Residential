<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Http\Form\FormFactory;
use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Domain\Exception\PropertyException;
use Adapter\Http\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
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
        $cmd = $this->formFactory->handleCreatePropertyForm($request->parsedBody)->command;

        $this->propertyService->create($cmd);
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
        $cmd = $this->formFactory->handleUpdatePropertyForm($request->parsedBody)->command;

        $this->propertyService->update($cmd);
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
        $cmd = $this->formFactory->handleDeletePropertyForm($request->parsedBody)->command;

        $this->propertyService->delete($cmd);
        return Response::json(['message' => 'Property deleted successfully']);
    }
}