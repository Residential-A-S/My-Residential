<?php

namespace Adapter\Http\Form;

use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\AlphaNumericRule;
use Adapter\Http\Form\Validation\RequiredRule;

class OrganizationCreateForm extends AbstractForm
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
