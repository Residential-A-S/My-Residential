<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\PaymentUpdateCommand;
use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class PaymentUpdateForm extends AbstractForm
{
    public PaymentUpdateCommand $command;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Payment_Update);

        $this
            ->addField('payment_id', [new RequiredRule(), new IntegerRule()])
            ->addField('amount', [new RequiredRule()])
            ->addField('currency', [new RequiredRule()])
            ->addField('due_at', [new RequiredRule()])
            ->addField('paid_at');
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new PaymentUpdateCommand(
            (int)$input['payment_id'],
            $input['amount'],
            $input['currency'],
            $input['due_at'],
            $input['paid_at'] ?: null
        );
    }
}
