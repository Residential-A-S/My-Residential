<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Adapter\Http\ResponseException;
use Shared\Exception\ServerException;
use Domain\Exception\UserException;
use Adapter\Http\Exception\ValidationException;
use Domain\Factory\FormFactory;
use Application\Service\AuthenticationService;
use Application\Service\UserService;

final readonly class UserController
{
    public function __construct(
        private UserService $userService,
        private AuthenticationService $authService,
        private FormFactory $formFactory,
    ) {}

    /**
     * @throws ValidationException
     * @throws ResponseException
     * @throws UserException
     */
    public function register(Request $request): Response
    {
        $form = $this->formFactory->handleRegisterForm($request->parsedBody);

        $user = $this->userService->create(
            $form->email,
            $form->password,
            $form->name
        );
        $request->session->regenerate();
        $request->session->set('user_id', $user->id);
        return Response::json(['message' => 'Registration successful.']);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws AuthenticationException
     * @throws ResponseException
     * @throws ValidationException|UserException
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
     *
     * @return Response
     * @throws ResponseException
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
 