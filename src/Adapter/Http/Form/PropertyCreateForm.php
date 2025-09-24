<?php

namespace Adapter\Http\Form;

use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class PropertyCreateForm extends AbstractForm
{
    public int $organizationId;
    public string $streetName;
    public string $streetNumber;
    public string $zipCode;
    public string $city;
    public string $country;
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
        //Write validated data to properties
        $this->organizationId = (int)$input['organization_id'];
        $this->streetName = $input['street_name'];
        $this->streetNumber = $input['street_number'];
        $this->zipCode = $input['zip_code'];
        $this->city = $input['city'];
        $this->country = $input['country'];
    }
}
