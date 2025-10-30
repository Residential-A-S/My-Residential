<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Exception\ResponseException;
use Adapter\Exception\ValidationException;
use Adapter\Http\Form\FormFactory;
use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Application\Service\AuthenticationService;
use Application\Service\RentChargeService;
use Domain\Exception\PaymentException;
use Domain\Exception\RentalAgreementException;
use Shared\Exception\ServerException;

final readonly class RentalAgreementPaymentController
{
    public function __construct(
        private RentChargeService $rentalAgreementPaymentService,
        private AuthenticationService $authService,
        private FormFactory $formFactory
    ) {
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws AuthenticationException
     * @throws RentalAgreementException
     * @throws ResponseException
     * @throws ServerException
     * @throws ValidationException
     * @throws PaymentException
     */
    public function create(Request $request): Response
    {
        $this->authService->requireUser();
        $cmd = $this->formFactory->handleCreateRentalAgreementPaymentForm($request->parsedBody)->command;

        $this->rentalAgreementPaymentService->create($cmd);

        return Response::json(['message' => 'Created rental agreement payment successfully']);
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
        $cmd = $this->formFactory->handleUpdateRentalAgreementPaymentForm($request->parsedBody)->command;

        $this->rentalAgreementPaymentService->update($cmd);

        return Response::json(['message' => 'Updated rental agreement payment successfully']);
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
        $cmd = $this->formFactory->handleDeleteRentalAgreementPaymentForm($request->parsedBody)->command;

        $this->rentalAgreementPaymentService->delete($cmd);

        return Response::json(['message' => 'Rental agreement payment deleted successfully']);
    }
}