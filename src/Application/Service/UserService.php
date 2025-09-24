<?php

namespace Application\Service;

use Application\DTO\View\User;
use Application\Exception\AuthenticationException;
use Application\Port\UserRepository;
use DateTimeImmutable;
use Domain\Exception\UserException;
use Domain\Factories\UserFactory;

final readonly class UserService
{
    public function __construct(
        private AuthenticationService $authService,
        private UserRepository $userRepository,
        private UserFactory $userFactory
    ) {}

    /**
     * @throws UserException
     */
    public function create(string $email, string $password, string $name): User
    {
        if ($this->userRepository->existsByEmail($email)) {
            throw new UserException(UserException::EMAIL_ALREADY_EXISTS);
        }

        return $this->userRepository->save(
            new User(
                id: null,
                email: $email,
                passwordHash: password_hash($password, PASSWORD_DEFAULT),
                name: $name,
                createdAt: new DateTimeImmutable(),
                updatedAt: new DateTimeImmutable(),
                lastLoginAt: null,
                failedLoginAttempts: 0
            )
        );
    }

    /**
     * @param string $name
     * @param string $email
     *
     * @throws AuthenticationException|UserException
     */
    public function update(string $name, string $email): void
    {
        $user = $this->authService->requireUser();
        $user = $this->userFactory->withUpdatedInfo($user, $name, $email);
        $this->userRepository->save($user);
    }

    /**
     * @param string $newPassword
     * @param string $repeatPassword
     *
     * @throws AuthenticationException
     * @throws UserException
     */
    public function updatePassword(string $newPassword, string $repeatPassword): void
    {
        $user = $this->authService->requireUser();
        if ($newPassword !== $repeatPassword) {
            throw new UserException(UserException::PASSWORDS_DO_NOT_MATCH);
        }
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatedUser  = $this->userFactory->withUpdatedPassword($user, $passwordHash);
        $this->userRepository->save($updatedUser);
    }

    /**
     * @throws AuthenticationException
     * @throws UserException
     */
    public function delete(): void
    {
        $user = $this->authService->requireUser();
        $this->userRepository->delete($user->id);
    }
}