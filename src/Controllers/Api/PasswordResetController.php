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
use src\Factories\FormFactory;
use src\Forms\ForgotPasswordSendVerificationForm;
use src\Services\PasswordResetService;

final readonly class PasswordResetController
{
    public function __construct(
        private PasswordResetService $passwordResetService,
        private FormFactory $formFactory,
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
        $form = $this->formFactory->handleForgotPasswordSendVerificationForm($request->parsedBody);

        $this->passwordResetService->sendVerification($form->email);
        return Response::json(['message' => 'Verification email sent.']);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws PasswordResetException
     * @throws ResponseException
     * @throws ServerException
     * @throws UserException
     * @throws ValidationException
     */
    public function resetPassword(Request $request): Response
    {
        $form = $this->formFactory->handleForgotPasswordResetPasswordForm($request->parsedBody);

        $this->passwordResetService->resetPassword(
            $form->token,
            $form->password,
            $form->repeatPassword
        );
        return Response::json(['message' => 'Password has been reset.']);
    }
}
