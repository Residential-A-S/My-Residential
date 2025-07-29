<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Forms\LoginForm;
use src\Forms\RegisterForm;
use src\Forms\ResetPasswordForm;
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
            $loginForm->data['email'],
            $loginForm->data['password']
        );
        $request->session->regenerate();
        $request->session->set('user_id', $user->id);
        return Response::json(['message' => 'Login successful.']);
    }

    public function logout(Request $request): Response
    {
        $this->authService->logout($request->session);
        return Response::json(['message' => 'Logout successful.']);
    }

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

    public function resetPassword(Request $request): Response
    {
        $resetPasswordForm = new ResetPasswordForm();
        $resetPasswordForm->handle($request->body);

        $this->authService->resetPassword($resetPasswordForm->data['password']);

        return Response::json(['message' => 'Password reset functionality not implemented yet.']);
    }
}