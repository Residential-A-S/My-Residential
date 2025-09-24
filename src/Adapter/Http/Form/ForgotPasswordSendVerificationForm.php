<?php

namespace Adapter\Http\Form;

use Adapter\Http\RouteName;
use Adapter\Http\Form\Validation\MaxRule;
use Adapter\Http\Form\Validation\RequiredRule;

class ForgotPasswordSendVerificationForm extends AbstractForm
{
    public string $email;
    public function __construct()
    {
        parent::__construct(RouteName::Api_Forgot_Password_Send_Verification);

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
