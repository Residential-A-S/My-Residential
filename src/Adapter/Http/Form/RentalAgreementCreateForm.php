<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\RentalAgreementCreateCommand;
use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class RentalAgreementCreateForm extends AbstractForm
{
    public RentalAgreementCreateCommand $command;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Rental_Agreement_Create);

        $this
            ->addField('rental_unit_id', [new RequiredRule(), new IntegerRule()])
            ->addField('start_date', [new RequiredRule()])
            ->addField('end_date')
            ->addField('status', [new RequiredRule()])
            ->addField('payment_interval', [new RequiredRule()]);
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new RentalAgreementCreateCommand(
            (int)$input['rental_unit_id'],
            $input['start_date'],
            $input['end_date'] ?? null,
            $input['status'],
            $input['payment_interval']
        );
    }
}
