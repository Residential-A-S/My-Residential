<?php

namespace Adapter\Http\Form;

use Adapter\Http\Form\Validation\MaxRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Adapter\Http\RouteName;
use Application\Dto\Command\UserUpdateCommand;

class UserUpdateForm extends AbstractForm
{
    public UserUpdateCommand $command;

    public function __construct()
    {
        parent::__construct(RouteName::Api_User_Update);

        $this
            ->addField(
                'email',
                [
                    new RequiredRule(),
                    new MaxRule(255)
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
        $this->command = new UserUpdateCommand(
            $input['email'],
            $input['name']
        );
    }
}
