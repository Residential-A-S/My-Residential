<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Adapter\Http\ResponseException;
use Adapter\Http\Exception\ValidationException;
use Adapter\Http\Form\FormFactory;
use Application\Service\AuthenticationService;

final readonly class AuthController
{
    public function __construct(
        private AuthenticationService $authService,
        private FormFactory $formFactory,
    ) {
    }

    /**
     * @throws ValidationException
     * @throws AuthenticationException
     * @throws ResponseException
     */
    public function login(Request $request): Response
    {
        $cmd = $this->formFactory->handleLoginForm($request->parsedBody)->command;

        $user = $this->authService->login($cmd);
        $request->session->regenerate();
        $request->session->set('user_id', $user->id);
        return Response::json(['message' => 'Login successful.']);
    }

    /**
     * @throws ResponseException
     * @throws AuthenticationException
     */
    public function logout(Request $request): Response
    {
        $this->authService->logout($request->session);
        return Response::json(['message' => 'Logout successful.']);
    }
}
