<?php

namespace src\Services;

use DateMalformedStringException;
use DateTime;
use Random\RandomException;
use src\Core\SessionInterface;
use src\Enums\Role;
use src\Exceptions\AuthenticationException;
use src\Exceptions\ConflictException;
use src\Exceptions\PasswordResetException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Factories\UserFactory;
use src\Models\User;
use src\Repositories\PasswordResetRepository;
use src\Repositories\UserRepository;

final readonly class PasswordResetService {
    public function __construct(
        private UserRepository $userRepository,
        private PasswordResetRepository $passwordResetRepository,
        private UserFactory $userFactory
    ) {}

    /**
     * @throws DateMalformedStringException
     * @throws RandomException
     * @throws ServerException
     * @throws UserException
     * @throws PasswordResetException
     */
    public function sendVerification(string $email): void
    {
        if(!$this->userRepository->existsByEmail($email)){
            throw new AuthenticationException(AuthenticationException::USER_NOT_FOUND);
        }
        $user = $this->userRepository->findByEmail($email);

        $token = bin2hex(random_bytes(32));
        $hashedToken = hash_hmac('sha256', $token, APP_SECRET);

        $expiresAt = new DateTime()->modify('+1 hour');

        $this->passwordResetRepository->insertPasswordResetToken($user->id, $hashedToken, $expiresAt);
        //TODO: send email with token
    }

    /**
     * @throws UserException
     * @throws ServerException
     * @throws PasswordResetException
     */
    public function resetPassword(string $token, int $user_id, string $newPassword): void
    {
        $hashedToken = hash_hmac('sha256', $token, APP_SECRET);
        $tokenArray = $this->passwordResetRepository->findByToken($hashedToken);
        if($tokenArray['user_id'] !== $user_id) {
            throw new PasswordResetException(PasswordResetException::INVALID);
        }
        if($tokenArray['expires_at'] < new DateTime()) {
            throw new PasswordResetException(PasswordResetException::EXPIRED);
        }
        $user = $this->userRepository->findById($user_id);
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        if($user->passwordHash === $passwordHash) {
            throw new PasswordResetException(PasswordResetException::CANNOT_REUSE_PASSWORD);
        }
        $updatedUser = $this->userFactory->withUpdatedPassword($user, $passwordHash);
        $this->userRepository->update($updatedUser);
        $this->passwordResetRepository->deleteByToken($hashedToken);
    }
}