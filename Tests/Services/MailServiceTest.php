<?php

namespace Tests\Services;

use PHPMailer\PHPMailer\Exception;
use src\Types\MailTemplates;
use Shared\Exception\ServerException;
use Tests\BaseTest;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailServiceTest extends BaseTest
{
    /**
     * @throws Exception
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws ServerException
     */
    public function testSend()
    {
        $this->mailService->send("test@gmail.com", "Test Subject", MailTemplates::PasswordReset);
    }
}
