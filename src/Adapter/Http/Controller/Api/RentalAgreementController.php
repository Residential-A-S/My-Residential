<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Exception\ResponseException;
use Adapter\Exception\ValidationException;
use Adapter\Http\Form\FormFactory;
use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Application\Service\AuthenticationService;
use Application\Service\RentalAgreementService;
use Domain\Exception\RentalAgreementException;
use Shared\Exception\ServerException;

final readonly class RentalAgreementController
{
    public function __construct(
        private RentalAgreementService $rentalAgreementService,
        private AuthenticationService $authService,
        private FormFactory $formFactory,
    ) {
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws ServerException
     * @throws AuthenticationException
     * @throws RentalAgreementException
     */
    public function create(Request $request): Response
    {
        $this->authService->requireUser();
        $cmd = $this->formFactory->handleCreateRentalAgreementForm($request->parsedBody)->command;

        $this->rentalAgreementService->create($cmd);

        return Response::json(['message' => 'Created rental agreement successfully']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws ServerException
     * @throws AuthenticationException
     * @throws RentalAgreementException
     */
    public function update(Request $request): Response
    {
        $this->authService->requireUser();
        $cmd = $this->formFactory->handleUpdateRentalAgreementForm($request->parsedBody)->command;

        $this->rentalAgreementService->update($cmd);

        return Response::json(['message' => 'Updated rental agreement successfully']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws AuthenticationException
     * @throws ServerException
     * @throws RentalAgreementException
     */
    public function delete(Request $request): Response
    {
        $this->authService->requireUser();
        $cmd = $this->formFactory->handleDeleteRentalAgreementForm($request->parsedBody)->command;

        $this->rentalAgreementService->delete($cmd);

        return Response::json(['message' => 'Rental agreement deleted successfully']);
    }
}
