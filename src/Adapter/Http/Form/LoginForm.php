<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\UserLoginCommand;
use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\MaxRule;
use Adapter\Http\Form\Validation\MinRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Adapter\Http\Form\Validation\StrongPasswordRule;

class LoginForm extends AbstractForm
{
    public UserLoginCommand $command;

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
        $this->command = new UserLoginCommand(
            $this->data['email'],
            $this->data['password']
        );
    }
}
