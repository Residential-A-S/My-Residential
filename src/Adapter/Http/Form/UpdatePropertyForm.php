<?php

namespace src\Forms;

use src\Types\RouteName;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;

class UpdatePropertyForm extends AbstractForm
{
    public int $propertyId;
    public string $streetName;
    public string $streetNumber;
    public string $zipCode;
    public string $city;
    public string $country;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Property_Update);

        $this
            ->addField(
                'property_id',
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
        $this->propertyId    = (int)$input['property_id'];
        $this->streetName    = $input['street_name'];
        $this->streetNumber = $input['street_number'];
        $this->zipCode = $input['zip_code'];
        $this->city = $input['city'];
        $this->country = $input['country'];
    }
}
