<?php

namespace Adapter\Http\Form;

use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Adapter\Http\RouteName;
use Application\Dto\Command\RentChargeDeleteCommand;

class RentChargeDeleteForm extends AbstractForm
{
    public RentChargeDeleteCommand $command;
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
        $this->command = new RentChargeDeleteCommand(
            (int)$input['rental_agreement_id']
        );
    }
}
