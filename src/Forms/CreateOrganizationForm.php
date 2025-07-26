<?php

namespace src\Forms;

use src\Enums\RouteNames;
use src\Validation\AlphaNumericRule;
use src\Validation\MaxRule;
use src\Validation\MinRule;
use src\Validation\RequiredRule;

class CreateOrganizationForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct(RouteNames::Login_POST);

        $this
            ->addField(
                'name',
                [
                    new RequiredRule(),
                    new AlphaNumericRule()
                ]
            );
    }
}