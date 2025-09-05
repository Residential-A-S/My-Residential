<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\AlphaNumericRule;
use src\Validation\RequiredRule;

class CreateOrganizationForm extends AbstractForm
{
    public string $name;
    public function __construct()
    {
        parent::__construct(RouteName::Login_POST);

        $this
            ->addField(
                'name',
                [
                    new RequiredRule(),
                    new AlphaNumericRule()
                ]
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        //Write validated data to properties
        $this->name = $input['name'];
    }
}
