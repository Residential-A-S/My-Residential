<?php

namespace src\Services;

use DateMalformedStringException;
use DateTime;
use DateTimeImmutable;
use Random\RandomException;
use src\Enums\MailTemplates;
use src\Exceptions\AuthenticationException;
use src\Exceptions\MailException;
use src\Exceptions\PasswordResetException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Factories\UserFactory;
use src\Repositories\PasswordResetRepository;
use src\Repositories\UserRepository;

final readonly class PasswordResetService
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordResetRepository $passwordResetRepository,
        private UserFactory $userFactory,
        private MailService $mailService,
    ) {
    }

    /**
     * @param string $email
     * @throws AuthenticationException
     * @throws DateMalformedStringException
     * @throws PasswordResetException
     * @throws RandomException
     * @throws ServerException
     * @throws UserException
     * @throws MailException
     */
    public function sendVerification(string $email): void
    {
        if (!$this->userRepository->existsByEmail($email)) {
            throw new AuthenticationException(AuthenticationException::USER_NOT_FOUND);
        }
        $user = $this->userRepository->findByEmail($email);

        $token = bin2hex(random_bytes(32));
        $hashedToken = hash_hmac('sha256', $token, APP_SECRET);

        $createdAt = new DateTimeImmutable();
        $expiresAt = $createdAt->modify('+1 hour');

        $this->passwordResetRepository->insertPasswordResetToken($user->id, $hashedToken, $expiresAt, $createdAt);
        $this->mailService->send(
            $email,
            'Password Reset Request',
            MailTemplates::PasswordReset,
            [
                'user' => $user,
                'token' => $token,
                'expiresAt' => $expiresAt->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @throws UserException
     * @throws ServerException
     * @throws PasswordResetException
     */
    public function resetPassword(string $token, string $newPassword, string $repeatNewPassword): void
    {
        $hashedToken = hash_hmac('sha256', $token, APP_SECRET);
        $passwordReset = $this->passwordResetRepository->findByToken($hashedToken);
        if ($passwordReset->expiresAt < new DateTime()) {
            throw new PasswordResetException(PasswordResetException::EXPIRED_TOKEN);
        }
        $user = $this->userRepository->findById($passwordReset->userId);
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        if ($user->passwordHash === $passwordHash) {
            throw new PasswordResetException(PasswordResetException::CANNOT_REUSE_PASSWORD);
        }
        $updatedUser = $this->userFactory->withUpdatedPassword($user, $passwordHash);
        $this->userRepository->update($updatedUser);
        $this->passwordResetRepository->deleteByToken($hashedToken);
    }
}
