<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Forms\UpdateUserForm;
use src\Services\UserService;

final readonly class UserController
{
    public function __construct(
        private UserService $userService
    ) {}

    public function update(Request $request): Response
    {
        $updateUserForm = new UpdateUserForm();
        $updateUserForm->handle($request->body);

        $this->userService->update(
            $updateUserForm->data['name'],
            $updateUserForm->data['email']
        );
        return Response::json(['message' => 'User update successful.']);
    }

    public function delete(Request $request): Response
    {
        $this->userService->delete();
        $request->session->clear();
        $request->session->regenerate();
        return Response::json(['message' => 'User delete successful.']);
    }
}