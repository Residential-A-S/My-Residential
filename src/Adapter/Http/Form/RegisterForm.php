<?php

namespace Adapter\Http\Form;

use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\MaxRule;
use Adapter\Http\Form\Validation\MinRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Adapter\Http\Form\Validation\StrongPasswordRule;

class RegisterForm extends AbstractForm
{
    public string $email;
    public string $password;
    public string $name;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Register);

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
            )
            ->addField(
                'name',
                [
                    new RequiredRule(),
                    new MaxRule(255)
                ]
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        //Write validated data to properties
        $this->email = $this->data['email'];
        $this->password = $this->data['password'];
        $this->name = $this->data['name'];
    }
}
