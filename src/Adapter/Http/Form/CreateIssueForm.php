<?php

namespace Adapter\Http\Form;

use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Adapter\Http\RouteName;
use Application\DTO\View\CreateIssueCommand;
use DateTimeImmutable;

class CreateIssueForm extends AbstractForm
{
    public CreateIssueCommand $issue;
    public int                $rentalAgreementId;
    public ?int $paymentId;
    public string $name;
    public string $description;
    public string $status;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Issue_Create);

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
        $this->issue = new CreateIssueCommand(
            id: null,
            rentalAgreementId: $this->rentalAgreementId,
            paymentId: $this->paymentId,
            name: $this->name,
            description: $this->description,
            status: $this->status,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );
    }
}
