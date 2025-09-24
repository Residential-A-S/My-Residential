<?php

namespace Adapter\Http\Form;

use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class PropertyDeleteForm extends AbstractForm
{
    public int $propertyId;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Property_Delete);

        $this
            ->addField(
                'property_id',
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
        $this->propertyId = (int)$input['property_id'];
    }
}
