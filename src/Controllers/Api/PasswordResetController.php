<?php

namespace src\Controllers\Api;

use DateMalformedStringException;
use Random\RandomException;
use src\Core\Request;
use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Exceptions\MailException;
use src\Exceptions\PasswordResetException;
use src\Exceptions\ResponseException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Exceptions\ValidationException;
use src\Forms\ForgotPasswordSendVerificationForm;
use src\Services\PasswordResetService;

final readonly class PasswordResetController
{
    public function __construct(
        private PasswordResetService $passwordResetService
    ) {
    }

    /**
     * @param Request $request
     * @return Response
     * @throws RandomException
     * @throws ResponseException
     * @throws ValidationException
     * @throws AuthenticationException
     * @throws MailException
     * @throws PasswordResetException
     * @throws ServerException
     * @throws UserException
     * @throws DateMalformedStringException
     */
    public function sendVerification(Request $request): Response
    {
        $forgotPasswordForm = new ForgotPasswordSendVerificationForm();
        $forgotPasswordForm->handle($request->body);

        $this->passwordResetService->sendVerification($forgotPasswordForm->data['email']);
        return Response::json(['message' => 'Verification email sent.']);
    }
}
