<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\PaymentCreateCommand;
use DateTimeImmutable;
use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\RequiredRule;
use Domain\ValueObject\Money;

class PaymentCreateForm extends AbstractForm
{
    public PaymentCreateCommand $command;
    public Money $money;
    public DateTimeImmutable $dueAt;
    public ?DateTimeImmutable $paidAt;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Payment_Create);

        $this
            ->addField('amount', [new RequiredRule()])
            ->addField('currency', [new RequiredRule()])
            ->addField('due_at', [new RequiredRule()])
            ->addField('paid_at');
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new PaymentCreateCommand(
            $input['amount'],
            $input['currency'],
            $input['due_at'],
            $input['paid_at'] ?: null
        );
    }
}
