<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\RentChargeCreateCommand;
use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class RentChargeCreateForm extends AbstractForm
{
    public RentChargeCreateCommand $command;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Rental_Agreement_Create);

        $this
            ->addField('rental_agreement_id', [new RequiredRule(), new IntegerRule()])
            ->addField('payment_id', [new IntegerRule()])
            ->addField('period_start', [new RequiredRule()])
            ->addField('period_end');
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new RentChargeCreateCommand(
            (int)$input['rental_agreement_id'],
            $input['payment_id'] ? (int)$input['payment_id'] : null,
            $input['period_start'],
            $input['period_end'] ?? null
        );
    }
}
