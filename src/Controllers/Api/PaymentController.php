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
use src\Services\AuthService;
use src\Services\PaymentService;

final readonly class PaymentController
{
    public function __construct(
        private PaymentService $paymentService,
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
        $form = $this->formFactory->handleCreatePaymentForm($request->parsedBody);

        $this->paymentService->create();

        return Response::json(['message' => 'Created payment successfully']);
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

        $this->paymentService->update(
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

        $this->paymentService->delete($form->propertyId);

        return Response::json(['message' => 'Property deleted successfully']);
    }
}
