<?php

namespace src\Factories;

use src\Exceptions\ValidationException;
use src\Forms\LoginForm;
use src\Forms\RegisterForm;

final readonly class FormFactory
{
    /**
     * @throws ValidationException
     */
    public function createRegisterForm(array $requestBody): RegisterForm
    {
        $form = new RegisterForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function createLoginForm(array $requestBody): LoginForm
    {
        $form = new LoginForm();
        $form->handle($requestBody);
        return $form;
    }
}
