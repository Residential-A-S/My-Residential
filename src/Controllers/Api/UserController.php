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
use src\Services\AuthService;
use src\Services\UserService;

final readonly class UserController
{
    public function __construct(
        private UserService $userService,
        private AuthService $authService,
        private FormFactory $formFactory,
    ) {
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthenticationException
     * @throws ResponseException
     * @throws ServerException
     * @throws ValidationException
     */
    public function update(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleUpdateUserForm($request->parsedBody);

        $this->userService->update(
            $form->name,
            $form->email
        );
        return Response::json(['message' => 'User update successful.']);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws AuthenticationException
     * @throws ResponseException
     * @throws ServerException
     * @throws ValidationException
     * @throws UserException
     */
    public function updatePassword(Request $request): Response
    {
        $this->authService->requireUser();
        $form = $this->formFactory->handleChangePasswordForm($request->parsedBody);

        $this->userService->updatePassword(
            $form->password,
            $form->repeatPassword
        );

        return Response::json(['message' => 'Password updated successfully.']);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ResponseException
     * @throws ServerException
     * @throws UserException
     * @throws AuthenticationException
     */
    public function delete(Request $request): Response
    {
        $this->userService->delete();
        $request->session->clear();
        $request->session->regenerate();
        return Response::json(['message' => 'User deleted successfully.']);
    }
}
