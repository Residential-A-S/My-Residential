<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\RentalAgreementUpdateCommand;
use DateMalformedStringException;
use DateTimeImmutable;
use Adapter\Http\RouteName;
use Adapter\Http\Exception\ValidationException;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Domain\Types\PaymentInterval;
use ValueError;

class RentalAgreementUpdateForm extends AbstractForm
{
    public RentalAgreementUpdateCommand $command;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Rental_Agreement_Update);

        $this
            ->addField('rental_agreement_id', [new RequiredRule(), new IntegerRule()])
            ->addField('rental_unit_id', [new RequiredRule(), new IntegerRule()])
            ->addField('start_date', [new RequiredRule()])
            ->addField('end_date')
            ->addField('status', [new RequiredRule()])
            ->addField('payment_interval', [new RequiredRule()]);
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new RentalAgreementUpdateCommand(
            (int)$input['rental_agreement_id'],
            (int)$input['rental_unit_id'],
            $input['start_date'],
            $input['end_date'] ?? null,
            $input['status'],
            $input['payment_interval']
        );
    }
}
