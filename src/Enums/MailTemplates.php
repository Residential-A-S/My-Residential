<?php

namespace src\Enums;

enum MailTemplates
{
    case PasswordReset;

    public function getTemplateName(): string
    {
        return match ($this) {
            self::PasswordReset => 'password-reset'
        };
    }
}
