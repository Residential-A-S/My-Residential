<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;

class DeleteIssueForm extends AbstractForm
{
    public int $issueId;
    public function __construct()
    {
        parent::__construct(RouteName::Login_POST);

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
        //Write validated data to properties
        $this->issueId = (int)$input['issue_id'];
    }
}
