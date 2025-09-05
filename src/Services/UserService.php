<?php

namespace src\Services;

use src\Exceptions\AuthenticationException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Factories\UserFactory;
use src\Repositories\UserRepository;

final readonly class UserService
{
    public function __construct(
        private AuthService $authService,
        private UserRepository $userRepository,
        private UserFactory $userFactory
    ) {
    }

    /**
     * @param string $name
     * @param string $email
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function update(string $name, string $email): void
    {
        $user = $this->authService->requireUser();
        if ($user->email === $email && $user->name === $name) {
            return;
        }
        $this->userRepository->update($user);
    }

    /**
     * @param string $newPassword
     * @param string $repeatPassword
     *
     * @throws AuthenticationException
     * @throws ServerException
     * @throws UserException
     */
    public function updatePassword(string $newPassword, string $repeatPassword): void
    {
        $user = $this->authService->requireUser();
        if ($newPassword !== $repeatPassword) {
            throw new UserException(UserException::PASSWORDS_DO_NOT_MATCH);
        }
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatedUser = $this->userFactory->withUpdatedPassword($user, $passwordHash);
        $this->userRepository->update($updatedUser);
    }

    /**
     * @throws AuthenticationException
     * @throws ServerException
     * @throws UserException
     */
    public function delete(): void
    {
        $user = $this->authService->requireUser();
        $this->userRepository->delete($user->id);
    }
}
