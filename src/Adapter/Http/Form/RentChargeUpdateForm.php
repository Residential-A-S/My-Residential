<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\RentChargeUpdateCommand;
use DateMalformedStringException;
use DateTimeImmutable;
use Adapter\Http\RouteName;
use Adapter\Http\Exception\ValidationException;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Domain\Types\PaymentInterval;
use ValueError;

class RentChargeUpdateForm extends AbstractForm
{
    public RentChargeUpdateCommand $command;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Rental_Agreement_Update);

        $this
            ->addField('rent_charge_id', [new RequiredRule(), new IntegerRule()])
            ->addField('rental_agreement_id', [new RequiredRule(), new IntegerRule()])
            ->addField('payment_id', [new IntegerRule()])
            ->addField('period_start', [new RequiredRule()])
            ->addField('period_end', [new RequiredRule()]);
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new RentChargeUpdateCommand(
            (int)$input['rent_charge_id'],
            (int)$input['rental_agreement_id'],
            $input['payment_id'] ? (int)$input['payment_id'] : null,
            $input['period_start'],
            $input['period_end']
        );
    }
}
