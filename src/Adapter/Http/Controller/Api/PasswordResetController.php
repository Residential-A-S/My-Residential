<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Exception\MailException;
use Adapter\Exception\ResponseException;
use Adapter\Exception\ValidationException;
use Adapter\Http\Form\FormFactory;
use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Application\Service\PasswordResetService;
use DateMalformedStringException;
use Domain\Exception\PasswordResetException;
use Domain\Exception\UserException;
use Random\RandomException;
use Shared\Exception\ServerException;

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
        $cmd = $this->formFactory->handleForgotPasswordSendVerificationForm($request->parsedBody)->command;

        $this->passwordResetService->sendVerification($cmd);
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
        $cmd = $this->formFactory->handleForgotPasswordResetPasswordForm($request->parsedBody)->command;

        $this->passwordResetService->resetPassword($cmd);
        return Response::json(['message' => 'Password has been reset.']);
    }
}
