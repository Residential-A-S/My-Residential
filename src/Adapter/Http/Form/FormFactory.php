<?php

namespace Adapter\Http\Form;

use Adapter\Exception\ValidationException;

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
     * CreateIssueCommand Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreateIssueForm(array $requestBody): IssueCreateForm
    {
        $form = new IssueCreateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdateIssueForm(array $requestBody): IssueUpdateForm
    {
        $form = new IssueUpdateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeleteIssueForm(array $requestBody): IssueDeleteForm
    {
        $form = new IssueDeleteForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * CreateOrganizationCommand Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreateOrganizationForm(array $requestBody): OrganizationCreateForm
    {
        $form = new OrganizationCreateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdateOrganizationForm(array $requestBody): OrganizationUpdateForm
    {
        $form = new OrganizationUpdateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeleteOrganizationForm(array $requestBody): OrganizationDeleteForm
    {
        $form = new OrganizationDeleteForm();
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
    public function handleCreatePropertyForm(array $requestBody): PropertyCreateForm
    {
        $form = new PropertyCreateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdatePropertyForm(array $requestBody): PropertyUpdateForm
    {
        $form = new PropertyUpdateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeletePropertyForm(array $requestBody): PropertyDeleteForm
    {
        $form = new PropertyDeleteForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * User Form
     */

    /**
     * @throws ValidationException
     */
    public function handleUpdateUserForm(array $requestBody): UserUpdateForm
    {
        $form = new UserUpdateForm();
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
    public function handleCreatePaymentForm(array $requestBody): PaymentCreateForm
    {
        $form = new PaymentCreateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdatePaymentForm(array $requestBody): PaymentUpdateForm
    {
        $form = new PaymentUpdateForm();
        $form->handle($requestBody);

        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeletePaymentForm(array $requestBody): PaymentDeleteForm
    {
        $form = new PaymentDeleteForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * Rental Agreement Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreateRentalAgreementForm(array $requestBody): RentalAgreementCreateForm
    {
        $form = new RentalAgreementCreateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdateRentalAgreementForm(array $requestBody): RentalAgreementUpdateForm
    {
        $form = new RentalAgreementUpdateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeleteRentalAgreementForm(array $requestBody): RentalAgreementDeleteForm
    {
        $form = new RentalAgreementDeleteForm();
        $form->handle($requestBody);
        return $form;
    }


    /**
     * Rental Agreement Payment Form
     */

    /**
     * @throws ValidationException
     */
    public function handleCreateRentalAgreementPaymentForm(array $requestBody): RentChargeCreateForm
    {
        $form = new RentChargeCreateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleUpdateRentalAgreementPaymentForm(array $requestBody): RentChargeUpdateForm
    {
        $form = new RentChargeUpdateForm();
        $form->handle($requestBody);
        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function handleDeleteRentalAgreementPaymentForm(array $requestBody): RentChargeDeleteForm
    {
        $form = new RentChargeDeleteForm();
        $form->handle($requestBody);
        return $form;
    }
}
