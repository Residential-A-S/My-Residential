<?php

namespace Application\Service;

use Application\Security\SessionInterface;
use Application\Exception\AuthenticationException;
use Application\Port\UserRepository;
use Shared\Exception\BaseException;
use Domain\Exception\UserException;
use Domain\Entity\User;


final readonly class AuthenticationService
{
    public function __construct(
        private SessionInterface $session,
        private UserRepository $userR
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
        } catch (UserException) {
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
     * @throws AuthenticationException
     */
    public function logout(SessionInterface $session): void
    {
        $this->requireUser();
        $session->clear();
        $session->regenerate();
    }
}
