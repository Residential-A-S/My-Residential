<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;

class UpdateIssueForm extends AbstractForm
{
    public int $issueId;
    public int $rentalAgreementId;
    public ?int $paymentId;
    public string $name;
    public string $description;
    public string $status;

    public function __construct()
    {
        parent::__construct(RouteName::Login_POST);

        $this
            ->addField('issue_id', [new RequiredRule(), new IntegerRule()])
            ->addField('rental_agreement_id', [new RequiredRule(), new IntegerRule()])
            ->addField('payment_id', [new IntegerRule()])
            ->addField('name', [new RequiredRule()])
            ->addField('description', [new RequiredRule()])
            ->addField('status', [new RequiredRule()]);
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        //Write validated data to properties
        $this->issueId = $this->data['issue_id'];
        $this->rentalAgreementId = $this->data['rental_agreement_id'];
        $this->paymentId = $this->data['payment_id'] ?? null;
        $this->name = $this->data['name'];
        $this->description = $this->data['description'];
        $this->status = $this->data['status'];
    }
}
