<?php

namespace src\Controllers\Api;

use DateMalformedStringException;
use Random\RandomException;
use src\Core\Request;
use src\Core\Response;
use src\Exceptions\ValidationException;
use src\Forms\ForgotPasswordSendVerificationForm;
use src\Services\PasswordResetService;

final readonly class PasswordResetController
{
    public function __construct(
        private PasswordResetService $passwordResetService
    ) {}

    /**
     * @throws DateMalformedStringException
     * @throws RandomException
     * @throws ValidationException
     */
    public function sendVerification(Request $request): Response
    {
        $forgotPasswordForm = new ForgotPasswordSendVerificationForm();
        $forgotPasswordForm->handle($request->body);

        $this->passwordResetService->sendVerification($forgotPasswordForm->data['email']);
        return Response::json(['message' => 'Verification email sent.']);
    }
}