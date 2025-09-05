<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Exceptions\ResponseException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Exceptions\ValidationException;
use src\Factories\FormFactory;
use src\Forms\LoginForm;
use src\Forms\RegisterForm;
use src\Services\AuthService;

final readonly class AuthController
{
    public function __construct(
        private AuthService $authService,
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
        $form = $this->formFactory->createLoginForm($request->parsedBody);

        $user = $this->authService->login(
            $form->email,
            $form->password
        );
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

    /**
     * @throws ValidationException
     * @throws ResponseException
     * @throws ServerException
     * @throws UserException
     */
    public function register(Request $request): Response
    {
        $form = $this->formFactory->createRegisterForm($request->parsedBody);

        $user = $this->authService->register(
            $form->email,
            $form->password,
            $form->name
        );
        $request->session->regenerate();
        $request->session->set('user_id', $user->id);
        return Response::json(['message' => 'Registration successful.']);
    }
}
