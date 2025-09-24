<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\PropertyCreateCommand;
use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class PropertyCreateForm extends AbstractForm
{
    public PropertyCreateCommand $command;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Property_Create);

        $this
            ->addField(
                'organization_id',
                [
                    new RequiredRule(),
                    new IntegerRule(),
                ]
            )
            ->addField(
                'street_name',
                [
                    new RequiredRule()
                ]
            )
            ->addField(
                'street_number',
                [
                    new RequiredRule()
                ]
            )
            ->addField(
                'zip_code',
                [
                    new RequiredRule()
                ]
            )
            ->addField(
                'city',
                [
                    new RequiredRule()
                ]
            )
            ->addField(
                'country',
                [
                    new RequiredRule()
                ]
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new PropertyCreateCommand(
            (int)$input['organization_id'],
            $input['street_name'],
            $input['street_number'],
            $input['zip_code'],
            $input['city'],
            $input['country']
        );
    }
}
