<?php

namespace Adapter\Http\Controllers\Api;

use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Domain\Exception\RentalAgreementException;
use Adapter\Http\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
use src\Factories\FormFactory;
use Application\Service\AuthenticationService;
use Application\Service\RentalAgreementService;

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
        $form = $this->formFactory->handleCreateRentalAgreementForm($request->parsedBody);

        $this->rentalAgreementService->create(
            $form->rentalUnitId,
            $form->startDate,
            $form->endDate,
            $form->status,
            $form->paymentInterval
        );

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
        $form = $this->formFactory->handleUpdateRentalAgreementForm($request->parsedBody);

        $this->rentalAgreementService->update(
            $form->rentalAgreementId,
            $form->rentalUnitId,
            $form->startDate,
            $form->endDate,
            $form->status,
            $form->paymentInterval
        );

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
        $form = $this->formFactory->handleDeleteRentalAgreementForm($request->parsedBody);

        $this->rentalAgreementService->delete($form->rentalAgreementId);

        return Response::json(['message' => 'Rental agreement deleted successfully']);
    }
}
