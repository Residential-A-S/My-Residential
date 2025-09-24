<?php

namespace Adapter\Http\Form;

use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\MaxRule;
use Adapter\Http\Form\Validation\RequiredRule;

class UserUpdateForm extends AbstractForm
{
    public string $email;
    public string $name;

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
        //Write validated data to properties
        $this->email = $this->data['email'];
        $this->name = $this->data['name'];
    }
}
