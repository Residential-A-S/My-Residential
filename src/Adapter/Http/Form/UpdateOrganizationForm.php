<?php

namespace src\Forms;

use src\Types\RouteName;
use src\Validation\AlphaNumericRule;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;

class UpdateOrganizationForm extends AbstractForm
{
    public int $organizationId;
    public string $name;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Organization_Update);

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
        $this->organizationId = (int)$input['organization_id'];
        $this->name = $input['name'];
    }
}
