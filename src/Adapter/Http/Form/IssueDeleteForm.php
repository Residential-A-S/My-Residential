<?php

namespace Adapter\Http\Form;

use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Adapter\Http\RouteName;
use Application\Dto\Command\IssueDeleteCommand;

class IssueDeleteForm extends AbstractForm
{
    public IssueDeleteCommand $command;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Issue_Delete);

        $this
            ->addField(
                'issue_id',
                [
                    new RequiredRule(),
                    new IntegerRule()
                ]
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);

        $this->command = new IssueDeleteCommand(
            (int)$input['issue_id']
        );
    }
}
