<?php

namespace src\Factories;

use Adapter\Http\Exception\ValidationException;
use src\Forms\ChangePasswordForm;
use src\Forms\CreateIssueForm;
use src\Forms\CreateOrganizationForm;
use src\Forms\CreatePaymentForm;
use src\Forms\CreatePropertyForm;
use src\Forms\CreateRentalAgreementForm;
use src\Forms\CreateRentalAgreementPaymentForm;
use src\Forms\DeleteIssueForm;
use src\Forms\DeleteOrganizationForm;
use src\Forms\DeletePaymentForm;
use src\Forms\DeletePropertyForm;
use src\Forms\DeleteRentalAgreementForm;
use src\Forms\DeleteRentalAgreementPaymentForm;
use src\Forms\DeleteUserForm;
use src\Forms\ForgotPasswordResetPasswordForm;
use src\Forms\ForgotPasswordSendVerificationForm;
use src\Forms\LoginForm;
use src\Forms\RegisterForm;
use src\Forms\UpdateIssueForm;
use src\Forms\UpdateOrganizationForm;
use src\Forms\UpdatePaymentForm;
use src\Forms\UpdatePropertyForm;
use src\Forms\UpdateRentalAgreementForm;
use src\Forms\UpdateRentalAgreementPaymentForm;
use src\Forms\UpdateUserForm;

final readonly class FormFactory
{
    /**
     * Authentication Form
     */

    /**
     * @throws ValidationException
     */
    public function handleRegisterForm(array $requestBody): RegisterForm
    {
        $form = new RegisterForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleLoginForm(array $requestBody): LoginForm
    {
        $form = new LoginForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * Issue Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreateIssueForm(array $requestBody): CreateIssueForm
    {
        $form = new CreateIssueForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdateIssueForm(array $requestBody): UpdateIssueForm
    {
        $form = new UpdateIssueForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeleteIssueForm(array $requestBody): DeleteIssueForm
    {
        $form = new DeleteIssueForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * Organization Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreateOrganizationForm(array $requestBody): CreateOrganizationForm
    {
        $form = new CreateOrganizationForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdateOrganizationForm(array $requestBody): UpdateOrganizationForm
    {
        $form = new UpdateOrganizationForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeleteOrganizationForm(array $requestBody): DeleteOrganizationForm
    {
        $form = new DeleteOrganizationForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * Password Reset Form
     */

    /**
     * @throws ValidationException
     */
    public function handleForgotPasswordSendVerificationForm(array $requestBody): ForgotPasswordSendVerificationForm
    {
        $form = new ForgotPasswordSendVerificationForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleForgotPasswordResetPasswordForm(array $requestBody): ForgotPasswordResetPasswordForm
    {
        $form = new ForgotPasswordResetPasswordForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * Property Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreatePropertyForm(array $requestBody): CreatePropertyForm
    {
        $form = new CreatePropertyForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdatePropertyForm(array $requestBody): UpdatePropertyForm
    {
        $form = new UpdatePropertyForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeletePropertyForm(array $requestBody): DeletePropertyForm
    {
        $form = new DeletePropertyForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * User Form
     */

    /**
     * @throws ValidationException
     */
    public function handleUpdateUserForm(array $requestBody): UpdateUserForm
    {
        $form = new UpdateUserForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleChangePasswordForm(array $requestBody): ChangePasswordForm
    {
        $form = new ChangePasswordForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * Payment Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreatePaymentForm(array $requestBody): CreatePaymentForm
    {
        $form = new CreatePaymentForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdatePaymentForm(array $requestBody): UpdatePaymentForm
    {
        $form = new UpdatePaymentForm();
        $form->handle($requestBody);

        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeletePaymentForm(array $requestBody): DeletePaymentForm
    {
        $form = new DeletePaymentForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * Rental Agreement Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreateRentalAgreementForm(array $requestBody): CreateRentalAgreementForm
    {
        $form = new CreateRentalAgreementForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdateRentalAgreementForm(array $requestBody): UpdateRentalAgreementForm
    {
        $form = new UpdateRentalAgreementForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeleteRentalAgreementForm(array $requestBody): DeleteRentalAgreementForm
    {
        $form = new DeleteRentalAgreementForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * Rental Agreement Payment Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreateRentalAgreementPaymentForm(array $requestBody): CreateRentalAgreementPaymentForm
    {
        $form = new CreateRentalAgreementPaymentForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdateRentalAgreementPaymentForm(array $requestBody): UpdateRentalAgreementPaymentForm
    {
        $form = new UpdateRentalAgreementPaymentForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeleteRentalAgreementPaymentForm(array $requestBody): DeleteRentalAgreementPaymentForm
    {
        $form = new DeleteRentalAgreementPaymentForm();
        $form->handle($requestBody);
        return $form;
    }
}
