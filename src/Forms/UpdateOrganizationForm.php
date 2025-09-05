<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\AlphaNumericRule;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;

class UpdateOrganizationForm extends AbstractForm
{
    public int $organization_id;
    public string $name;
    public function __construct()
    {
        parent::__construct(RouteName::Login_POST);

        $this
            ->addField(
                'organization_id',
                [
                    new RequiredRule(),
                    new IntegerRule()
                ]
            )
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
        $this->organization_id = (int)$input['organization_id'];
        $this->name = $input['name'];
    }
}
