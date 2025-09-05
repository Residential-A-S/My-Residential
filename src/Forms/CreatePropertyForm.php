<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;

class CreatePropertyForm extends AbstractForm
{
    public int $organization_id;
    public string $street_name;
    public string $street_number;
    public string $zip_code;
    public string $city;
    public string $country;
    public function __construct()
    {
        parent::__construct(RouteName::Login_POST);

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
        $this->organization_id = (int)$input['organization_id'];
        $this->street_name = $input['street_name'];
        $this->street_number = $input['street_number'];
        $this->zip_code = $input['zip_code'];
        $this->city = $input['city'];
        $this->country = $input['country'];
    }
}
