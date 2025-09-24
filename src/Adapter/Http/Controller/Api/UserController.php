<?php

namespace Adapter\Http\Controller\Api;

use Adapter\Http\Form\FormFactory;
use Adapter\Http\Request;
use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Adapter\Http\ResponseException;
use Domain\Exception\UserException;
use Adapter\Http\Exception\ValidationException;
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
        $cmd = $this->formFactory->handleRegisterForm($request->parsedBody)->command;

        $user = $this->userService->create($cmd);
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
        $cmd = $this->formFactory->handleUpdateUserForm($request->parsedBody)->command;

        $this->userService->update($cmd);

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
        $cmd = $this->formFactory->handleChangePasswordForm($request->parsedBody)->command;

        $this->userService->updatePassword($cmd);

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
 