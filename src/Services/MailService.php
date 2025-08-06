<?php

namespace src\Services;

use PHPMailer\PHPMailer\Exception;
use src\Enums\MailTemplates;
use src\Exceptions\ServerException;
use Twig\Environment;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class MailService
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    /**
     * @throws Exception
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws ServerException
     */
    public function send(
        $to,
        string $subject,
        MailTemplates $mailTemplate,
        array $context = [],
        string $from = 'noreply@propeteer.app'
    ): void {
        $html = $this->twig->render(
            'emails/' . $mailTemplate->getTemplateName() . '.twig',
            $context
        );

        $email = new PHPMailer();
        $email->isSMTP();
        $email->Host = SMTP_HOST;
        $email->Port = SMTP_PORT;
        $email->Username = SMTP_USER;
        $email->Password = SMTP_PASSWORD;
        $email->setFrom($from, 'Propeteer');
        $email->SMTPAuth = true;
        $email->addAddress($to);
        $email->Subject = $subject;
        $email->isHTML();
        $email->Body = $html;
        $email->AltBody = strip_tags($html);
        if (!$email->send()) {
            throw new ServerException('Email could not be sent: ' . $email->ErrorInfo);
        }
    }
}
