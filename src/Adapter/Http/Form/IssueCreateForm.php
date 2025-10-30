<?php

namespace Adapter\Http\Form;

use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Adapter\Http\RouteName;
use Application\Dto\Command\IssueCreateCommand;

class IssueCreateForm extends AbstractForm
{
    public IssueCreateCommand $command;

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

        $this->command = new IssueCreateCommand(
            $this->data['rental_agreement_id'],
            $this->data['payment_id'] ?? null,
            $this->data['name'],
            $this->data['description'],
            $this->data['status']
        );
    }
}
