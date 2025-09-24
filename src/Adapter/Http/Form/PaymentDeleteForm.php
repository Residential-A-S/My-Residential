<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\PaymentDeleteCommand;
use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class PaymentDeleteForm extends AbstractForm
{
    public PaymentDeleteCommand $command;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Payment_Delete);

        $this
            ->addField(
                'payment_id',
                [
                    new RequiredRule(),
                    new IntegerRule()
                ]
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new PaymentDeleteCommand(
            (int)$input['payment_id']
        );
    }
}
