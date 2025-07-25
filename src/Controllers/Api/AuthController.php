<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Forms\LoginForm;
use src\Forms\RegisterForm;
use src\Services\AuthService;

final readonly class AuthController
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function login(Request $request): Response
    {
        $loginForm = new LoginForm();
        $loginForm->handle($request->body);

        $user = $this->authService->login(
            $loginForm->data['username'],
            $loginForm->data['password']
        );
        $request->session->regenerate();
        $request->session->set('student_id', $user->id);
        return Response::json(['message' => 'Login successful.']);
    }

    public function logout(Request $request): Response
    {
        $this->authService->requireUser();
        $request->session->clear();
        $request->session->regenerate();
        return Response::json(['message' => 'Logout successful.']);
    }

    public function register(Request $request): Response
    {
        $registerForm = new RegisterForm();
        $registerForm->handle($request->body);

        $user = $this->authService->register(
            $registerForm->data['username'],
            $registerForm->data['password']
        );
        $request->session->regenerate();
        $request->session->set('user_id', $user->id);
        return Response::json(['message' => 'Registration successful.']);
    }
}