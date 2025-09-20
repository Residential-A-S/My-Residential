<?php

namespace Adapter\Http\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use Application\Exception\AuthenticationException;
use Domain\Exception\PaymentException;
use Domain\Exception\RentalAgreementException;
use src\Core\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
use src\Factories\FormFactory;
use Application\Service\AuthenticationService;
use Application\Service\RentalAgreementPaymentService;
use Application\Service\RentalAgreementService;
use src\UseCases\RentalAgreementPaymentUseCases;

final readonly class RentalAgreementPaymentController
{
    public function __construct(
        private RentalAgreementPaymentService $rentalAgreementPaymentService,
        private AuthenticationService $authService,
        private FormFactory $formFactory,
        private RentalAgreementPaymentUseCases $rentalAgreementPaymentUseCases,
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
        $form = $this->formFactory->handleCreateRentalAgreementPaymentForm($request->parsedBody);

        $this->rentalAgreementPaymentUseCases->create(
            rentalAgreementId: $form->rentalAgreementId,
            periodStart: $form->periodStart,
            periodEnd: $form->periodEnd,
            amount: $form->amount,
            currency: $form->currency,
            dueAt: $form->dueAt,
            paidAt: $form->paidAt,
        );

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
        $form = $this->formFactory->handleUpdateRentalAgreementPaymentForm($request->parsedBody);

        $this->rentalAgreementPaymentService->update(
            $form->rentalAgreementId,
            $form->rentalUnitId,
            $form->startDate,
            $form->endDate,
            $form->status,
            $form->paymentInterval
        );

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
        $form = $this->formFactory->handleDeleteRentalAgreementPaymentForm($request->parsedBody);

        $this->rentalAgreementPaymentService->delete($form->rentalAgreementId);

        return Response::json(['message' => 'Rental agreement payment deleted successfully']);
    }
}
