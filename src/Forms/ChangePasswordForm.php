<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\MaxRule;
use src\Validation\MinRule;
use src\Validation\RequiredRule;
use src\Validation\StrongPasswordRule;

class ChangePasswordForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct(RouteName::Register);

        $this
            ->addField(
                'password',
                [
                    new RequiredRule(),
                    new MinRule(8),
                    new MaxRule(255),
                    new StrongPasswordRule()
                ]
            );
    }
}
