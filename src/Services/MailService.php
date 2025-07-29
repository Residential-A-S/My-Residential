<?php

namespace src\Services;

use src\Enums\MailTemplates;
use Twig\Environment;
use PHPMailer\PHPMailer\PHPMailer;

final readonly class MailService
{
    public function __construct(
        private Environment $twig,
    ) {}

    public function send(
        $to,
        string $subject,
        string $template,
        MailTemplates $mailTemplate,
        array $context = [],
        string $from = 'noreply@propeteer.app'
    ): void
    {
        return;
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
            throw new \RuntimeException('Email could not be sent: ' . $email->ErrorInfo);
        }
    }
}