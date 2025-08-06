<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\MaxRule;
use src\Validation\RequiredRule;

class ForgotPasswordSendVerificationForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct(RouteName::Register);

        $this
            ->addField(
                'email',
                [
                    new RequiredRule(),
                    new MaxRule(255)
                ]
            );
    }
}
