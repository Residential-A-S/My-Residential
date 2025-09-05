<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;

class DeleteOrganizationForm extends AbstractForm
{
    public int $organizationId;
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
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        //Write validated data to properties
        $this->organizationId = (int)$input['organization_id'];
    }
}
