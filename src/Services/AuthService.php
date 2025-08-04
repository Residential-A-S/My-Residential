<?php

namespace src\Services;

use DateTimeImmutable;
use src\Core\SessionInterface;
use src\Enums\Permission;
use src\Exceptions\AuthenticationException;
use src\Exceptions\BaseException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Models\User;
use src\Repositories\UserOrganizationRepository;
use src\Repositories\UserRepository;

final readonly class AuthService
{
    public function __construct(
        private SessionInterface $session,
        private UserRepository $userR,
        private UserOrganizationRepository $userOrgRelR
    ) {
    }

    /**
     * Returns the currently logged-in user, or null if none.
     */
    public function getCurrentUser(): ?User
    {
        $id = $this->session->get('user_id');
        if ($id === null) {
            return null;
        }
        try {
            return $this->userR->findById((int)$id);
        } catch (ServerException | UserException) {
            return null;
        }
    }

    /**
     * Asserts that a user is logged in, or throws.
     *
     * @throws AuthenticationException
     */
    public function requireUser(): User
    {
        $user = $this->getCurrentUser();
        if (! $user) {
            throw new AuthenticationException(AuthenticationException::MUST_BE_LOGGED_IN);
        }
        return $user;
    }

    /**
     * @throws AuthenticationException
     */
    public function login(string $email, string $password): User
    {
        try {
            $user = $this->userR->findByEmail($email);
        } catch (BaseException) {
            throw new AuthenticationException(AuthenticationException::USER_NOT_FOUND);
        }

        if (!password_verify($password, $user->passwordHash)) {
            throw new AuthenticationException(AuthenticationException::INVALID_PASSWORD);
        }
        return $user;
    }

    /**
     * @throws UserException
     * @throws ServerException
     */
    public function register(string $email, string $password, string $name): User
    {
        if ($this->userR->existsByEmail($email)) {
            throw new UserException(UserException::EMAIL_ALREADY_EXISTS);
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        return $this->userR->create(
            new User(
                id: 0,
                email: $email,
                passwordHash: $passwordHash,
                name: $name,
                createdAt: new DateTimeImmutable(),
                updatedAt: new DateTimeImmutable(),
                lastLoginAt: null,
                failedLoginAttempts: 0
            )
        );
    }

    /**
     * @throws AuthenticationException
     */
    public function logout(SessionInterface $session): void
    {
        $this->requireUser();
        $session->clear();
        $session->regenerate();
    }

    public function canUserPerformAction(int $userId, int $orgId, Permission $permission): bool
    {
        $role = $this->userOrgRelR->findUserRoleInOrganization($userId, $orgId);
        return $role->hasPermission($permission);
    }

    /**
     * @throws AuthenticationException
     */
    public function canCurrentUserPerformAction(int $orgId, Permission $permission): bool
    {
        $user = $this->requireUser();
        return $this->canUserPerformAction($user->id, $orgId, $permission);
    }
}
