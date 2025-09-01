<?php

namespace src\Controllers\Api;

use src\Core\Request;
use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Exceptions\ResponseException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Exceptions\ValidationException;
use src\Forms\ChangePasswordForm;
use src\Forms\UpdateUserForm;
use src\Services\IssueService;

final readonly class IssueController
{
    public function __construct(
        private IssueService $issueService
    ) {
    }

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

    /**
     * @param Request $request
     * @return Response
     * @throws AuthenticationException
     * @throws ResponseException
     * @throws ServerException
     * @throws ValidationException
     */
    public function updatePassword(Request $request): Response
    {
        $changePasswordForm = new ChangePasswordForm();
        $changePasswordForm->handle($request->body);

        $this->userService->updatePassword($changePasswordForm->data['password']);

        return Response::json(['message' => 'Password reset successful.']);
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
        return Response::json(['message' => 'User delete successful.']);
    }
}
