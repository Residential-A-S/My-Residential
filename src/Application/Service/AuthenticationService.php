<?php

namespace Application\Service;

use Application\Dto\Command\UserLoginCommand;
use Application\Exception\AuthenticationException;
use Application\Port\UserRepository;
use Application\Security\SessionInterface;
use Domain\Entity\User;


/**
 *
 */
final readonly class AuthenticationService
{
    /**
     * @param SessionInterface $session
     * @param UserRepository $userRepository
     */
    public function __construct(
        private SessionInterface $session,
        private UserRepository $userRepository
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
        return $this->userRepository->findById((int)$id);
    }

    /**
     * Asserts that a user is logged in, or throws.
     *
     * @throws AuthenticationException
     */
    public function requireUser(): User
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            throw new AuthenticationException(AuthenticationException::MUST_BE_LOGGED_IN);
        }
        return $user;
    }

    /**
     * @throws AuthenticationException
     */
    public function login(UserLoginCommand $cmd): User
    {
        $user = $this->userRepository->findByEmail($cmd->email);
        if (!$user) {
            throw new AuthenticationException(AuthenticationException::USER_NOT_FOUND);
        }

        if (!$user->passwordHash->verify($cmd->password)) {
            throw new AuthenticationException(AuthenticationException::INVALID_PASSWORD);
        }
        return $user;
    }



    /**
     * @throws AuthenticationException
     */
    public function logout(): void
    {
        $this->requireUser();
        $this->session->clear();
        $this->session->regenerate();
    }
}
