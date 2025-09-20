<?php

namespace src\Forms;

use src\Types\RouteName;
use src\Validation\MaxRule;
use src\Validation\MinRule;
use src\Validation\RequiredRule;
use src\Validation\StrongPasswordRule;

class LoginForm extends AbstractForm
{
    public string $email;
    public string $password;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Login);

        $this
            ->addField(
                'email',
                [
                    new RequiredRule(),
                    new MaxRule(255)
                ]
            )
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

    public function handle(array $input): void
    {
        parent::handle($input);
        //Write validated data to properties
        $this->email = $this->data['email'];
        $this->password = $this->data['password'];
    }
}
