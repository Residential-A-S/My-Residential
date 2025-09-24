<?php

namespace Adapter\Http\Form;

use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class DeleteRentalAgreementForm extends AbstractForm
{
    public int $rentalAgreementId;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Rental_Agreement_Delete);

        $this
            ->addField(
                'rental_agreement_id',
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
        $this->rentalAgreementId = (int)$input['rental_agreement_id'];
    }
}
