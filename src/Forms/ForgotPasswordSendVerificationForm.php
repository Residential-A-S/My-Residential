<?php

namespace src\Forms;

use src\Enums\RouteName;
use src\Validation\MaxRule;
use src\Validation\RequiredRule;

class ForgotPasswordSendVerificationForm extends AbstractForm
{
    public string $email;
    public function __construct()
    {
        parent::__construct(RouteName::Register);

        $this
            ->addField(
                'email',
                [
                    new RequiredRule(),
                    new MaxRule(255)
                ]
            );
    }

    public function handle(array $input): void
    {
        parent::handle($input);
        //Write validated data to properties
        $this->email = $input['email'];
    }
}
