<?php

namespace src\Forms;

use src\Enums\RouteNames;
use src\Validation\AlphaNumericRule;
use src\Validation\MaxRule;
use src\Validation\MinRule;
use src\Validation\RequiredRule;
use src\Validation\StrongPasswordRule;

class RegisterForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct(RouteNames::Register);

        $this
            ->addField(
                'username',
                [
                    new RequiredRule(),
                    new AlphaNumericRule(),
                    new MinRule(5),
                    new MaxRule(40)
                ]
            )
            ->addField(
                'password',
                [
                    new RequiredRule(),
                    new MinRule(8),
                    new MaxRule(32),
                    new StrongPasswordRule()
                ]
            );
    }
}