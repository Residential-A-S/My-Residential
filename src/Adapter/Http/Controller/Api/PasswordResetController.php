<?php

namespace Adapter\Http\Controllers\Api;

use DateMalformedStringException;
use Random\RandomException;
use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Adapter\Mail\MailException;
use Domain\Exception\PasswordResetException;
use Adapter\Http\ResponseException;
use Shared\Exception\ServerException;
use Domain\Exception\UserException;
use Adapter\Http\Exception\ValidationException;
use src\Factories\FormFactory;
use src\Forms\ForgotPasswordSendVerificationForm;
use Application\Service\PasswordResetService;

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
