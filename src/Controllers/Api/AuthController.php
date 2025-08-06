<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Exceptions\ResponseException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Exceptions\ValidationException;
use src\Forms\LoginForm;
use src\Forms\RegisterForm;
use src\Services\AuthService;

final readonly class AuthController
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    /**
     * @throws ValidationException
     * @throws AuthenticationException
     * @throws ResponseException
     */
    public function login(Request $request): Response
    {
        $loginForm = new LoginForm();
        $loginForm->handle($request->body);

        $user = $this->authService->login(
            $loginForm->data['email'],
            $loginForm->data['password']
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
        $registerForm = new RegisterForm();
        $registerForm->handle($request->body);

        $user = $this->authService->register(
            $registerForm->data['email'],
            $registerForm->data['password'],
            $registerForm->data['name']
        );
        $request->session->regenerate();
        $request->session->set('user_id', $user->id);
        return Response::json(['message' => 'Registration successful.']);
    }
}
