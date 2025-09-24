<?php

namespace Adapter\Http\Form;

use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\AlphaNumericRule;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class OrganizationUpdateForm extends AbstractForm
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
