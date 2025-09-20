<?php

namespace src\Forms;

use src\Types\RouteName;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;

class DeletePaymentForm extends AbstractForm
{
    public int $paymentId;
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
        //Write validated data to properties
        $this->paymentId = (int)$input['payment_id'];
    }
}
