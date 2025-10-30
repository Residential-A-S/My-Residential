<?php

namespace Adapter\Http\Form;

use Adapter\Http\Form\Validation\IntegerRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Adapter\Http\RouteName;
use Application\Dto\Command\OrganizationDeleteCommand;

class OrganizationDeleteForm extends AbstractForm
{
    public OrganizationDeleteCommand $command;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Organization_Delete);

        $this
            ->addField(
                'organization_id',
                [
                    new RequiredRule(),
                    new IntegerRule()
                ]
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new OrganizationDeleteCommand(
            (int)$input['organization_id']
        );
    }
}
