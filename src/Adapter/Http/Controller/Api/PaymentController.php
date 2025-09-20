<?php

namespace Adapter\Http\Controllers\Api;

use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Domain\Exception\PaymentException;
use Adapter\Http\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
use src\Factories\FormFactory;
use Application\Service\AuthenticationService;
use Application\Service\PaymentService;

final readonly class PaymentController
{
    public function __construct(
        private PaymentService $paymentService,
        private AuthenticationService $authService,
        private FormFactory $formFactory,
    ) {
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws ServerException
     * @throws AuthenticationException
     * @throws PaymentException
     */
    public function create(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleCreatePaymentForm($request->parsedBody);

        $this->paymentService->create(
            $form->amount,
            $form->currency,
            $form->dueAt,
            $form->paidAt
        );

        return Response::json(['message' => 'Created payment successfully']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws ServerException
     * @throws AuthenticationException
     * @throws PaymentException
     */
    public function update(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleUpdatePaymentForm($request->parsedBody);

        $this->paymentService->update(
            $form->paymentId,
            $form->amount,
            $form->currency,
            $form->dueAt,
            $form->paidAt
        );

        return Response::json(['message' => 'Updated payment successfully']);
    }

    /**
     * @throws ResponseException
     * @throws ValidationException
     * @throws AuthenticationException
     * @throws ServerException
     * @throws PaymentException
     */
    public function delete(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleDeletePaymentForm($request->parsedBody);

        $this->paymentService->delete($form->paymentId);

        return Response::json(['message' => 'Payment deleted successfully']);
    }
}
