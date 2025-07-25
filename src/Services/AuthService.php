<?php

namespace src\Services;

use src\Core\SessionInterface;
use src\Exceptions\AuthenticationException;
use src\Exceptions\ConflictException;
use src\Exceptions\ServerException;
use src\Models\User;
use src\Repositories\UserRepository;

final readonly class AuthService {
    public function __construct(
        private SessionInterface $session,
        private UserRepository $userRepository,
    ) {}

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
            return $this->userRepository->findById((int)$id);
        } catch (ServerException) {
            return null;
        }
    }

    /**
     * @return bool  True if there is a valid logged-in user.
     */
    public function isAuthenticated(): bool
    {
        return $this->getCurrentUser() !== null;
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

    public function login(string $username, string $password): User
    {
        try {
            $user = $this->userRepository->findByEmail($username);
        } catch (ServerException){
            throw new AuthenticationException(AuthenticationException::USER_NOT_FOUND);
        }

        if(!password_verify($password, $user->password)){
            throw new AuthenticationException(AuthenticationException::INVALID_PASSWORD);
        }
        return $user;
    }

    public function register(string $email, string $password): User
    {
        if ($this->userRepository->existsByEmail($email)){
            throw new ConflictException(ConflictException::USER_ALREADY_EXISTS);
        }

        $user['email'] = $email;
        $user['password'] = password_hash($password, PASSWORD_DEFAULT);
        return $this->userRepository->create($user);
    }
}