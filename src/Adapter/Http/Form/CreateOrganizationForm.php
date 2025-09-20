<?php

namespace src\Forms;

use src\Types\RouteName;
use src\Validation\AlphaNumericRule;
use src\Validation\RequiredRule;

class CreateOrganizationForm extends AbstractForm
{
    public string $name;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Organization_Create);

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
