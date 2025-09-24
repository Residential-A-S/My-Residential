<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Http\Form\FormFactory;
use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Domain\Exception\PaymentException;
use Adapter\Http\ResponseException;
use Shared\Exception\ServerException;
use Adapter\Http\Exception\ValidationException;
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
        $cmd = $this->formFactory->handleCreatePaymentForm($request->parsedBody)->command;

        $this->paymentService->create($cmd);

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
        $cmd = $this->formFactory->handleUpdatePaymentForm($request->parsedBody)->command;

        $this->paymentService->update($cmd);

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
        $cmd = $this->formFactory->handleDeletePaymentForm($request->parsedBody)->command;

        $this->paymentService->delete($cmd);

        return Response::json(['message' => 'Payment deleted successfully']);
    }
}
