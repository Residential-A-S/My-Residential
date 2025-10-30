<?php

namespace Adapter\Http\Form;

use Adapter\Http\Form\Validation\AlphaNumericRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Adapter\Http\RouteName;
use Application\Dto\Command\OrganizationCreateCommand;

class OrganizationCreateForm extends AbstractForm
{
    public OrganizationCreateCommand $command;
    public string $name;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Organization_Create);

        $this
            ->addField(
                'name',
                [
                    new RequiredRule(),
                    new AlphaNumericRule()
                ]
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        $this->command = new OrganizationCreateCommand(
            $input['name']
        );
    }
}
