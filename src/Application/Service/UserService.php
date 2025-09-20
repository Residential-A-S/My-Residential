<?php

namespace Application\Service;

use DateTimeImmutable;
use src\Entity\User;
use Application\Exception\AuthenticationException;
use Shared\Exception\ServerException;
use Domain\Exception\UserException;
use src\Factories\UserFactory;
use Adapter\Persistence\UserRepository;
use Application\Service\AuthenticationService;

final readonly class UserService
{
    public function __construct(
        private AuthenticationService $authService,
        private UserRepository $userRepository,
        private UserFactory $userFactory
    ) {
    }

    /**
     * @throws UserException
     * @throws ServerException
     */
    public function create(string $email, string $password, string $name): User
    {
        if ($this->userRepository->existsByEmail($email)) {
            throw new UserException(UserException::EMAIL_ALREADY_EXISTS);
        }

        return $this->userRepository->create(
            new User(
                id: 0,
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