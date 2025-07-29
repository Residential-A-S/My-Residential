<?php

namespace src\Forms;

use src\Enums\RouteNames;
use src\Validation\AlphaNumericRule;
use src\Validation\MaxRule;
use src\Validation\MinRule;
use src\Validation\RequiredRule;
use src\Validation\StrongPasswordRule;

class ForgotPasswordSendVerificationForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct(RouteNames::Register);

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