<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;

class CreateIssueForm extends AbstractForm
{
    public int $rentalAgreementId;
    public ?int $paymentId;
    public string $name;
    public string $description;
    public string $status;

    public function __construct()
    {
        parent::__construct(RouteName::Login_POST);

        $this
            ->addField('rentalAgreementId', [new RequiredRule(), new IntegerRule()])
            ->addField('paymentId', [new IntegerRule()])
            ->addField('name', [new RequiredRule()])
            ->addField('description', [new RequiredRule()])
            ->addField('status', [new RequiredRule()]);
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        //Write validated data to properties
        $this->rentalAgreementId = $this->data['rental_agreement_id'];
        $this->paymentId = $this->data['payment_id'] ?? null;
        $this->name = $this->data['name'];
        $this->description = $this->data['description'];
        $this->status = $this->data['status'];
    }
}
