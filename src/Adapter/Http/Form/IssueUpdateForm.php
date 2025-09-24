<?php

namespace Adapter\Http\Form;

use Adapter\Dto\Command\IssueUpdateCommand;
use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;

class IssueUpdateForm extends AbstractForm
{
    public IssueUpdateCommand $command;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Issue_Update);

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

        $this->command = new IssueUpdateCommand(
            (int)$this->data['issue_id'],
            (int)$this->data['rental_agreement_id'],
            isset($this->data['payment_id']) ? (int)$this->data['payment_id'] : null,
            $this->data['name'],
            $this->data['description'],
            $this->data['status']
        );
    }
}
