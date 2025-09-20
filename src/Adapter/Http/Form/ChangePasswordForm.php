<?php

namespace src\Forms;

use src\Types\RouteName;
use src\Validation\MaxRule;
use src\Validation\MinRule;
use src\Validation\RequiredRule;
use src\Validation\StrongPasswordRule;

class ChangePasswordForm extends AbstractForm
{
    public string $password;
    public string $repeatPassword;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Change_Password);

        $this
            ->addField(
                'password',
                [
                    new RequiredRule(),
                    new MinRule(8),
                    new MaxRule(255),
                    new StrongPasswordRule()
                ]
            )
            ->addField(
                'repeat_password',
                [
                    new RequiredRule(),
                    new MinRule(8),
                    new MaxRule(255),
                    new StrongPasswordRule()
                ]
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        //Write validated data to properties
        $this->password = $input['password'];
        $this->repeatPassword = $input['repeat_password'];
    }
}
