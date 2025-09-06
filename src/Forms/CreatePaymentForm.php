<?php

namespace src\Forms;

use DateMalformedStringException;
use DateTimeImmutable;
use src\Enums\Currency;
use src\Enums\RouteName;
use src\Exceptions\ValidationException;
use src\Validation\NumberRule;
use src\Validation\RequiredRule;
use ValueError;

class CreatePaymentForm extends AbstractForm
{
    public int $amount;
    public Currency $currency;
    public DateTimeImmutable $dueAt;
    public ?DateTimeImmutable $paidAt;

    public function __construct()
    {
        parent::__construct(RouteName::Login_POST);

        $this
            ->addField('amount', [new RequiredRule(), new NumberRule()])
            ->addField('currency', [new RequiredRule()])
            ->addField('due_at', [new RequiredRule()])
            ->addField('paid_at');
    }

    public function handle(array $input): void
    {
        try {
            parent::handle($input);
            //Write validated data to properties
            $this->amount   = (int)$input['amount'];
            $this->currency = Currency::from($input['currency']);
            $this->dueAt    = new DateTimeImmutable($input['due_at']);
            $this->paidAt   = $input['paid_at'] ? new DateTimeImmutable($input['paid_at']) : null;
        } catch (ValueError) {
            $this->errors['currency'] = 'Invalid currency value';
            throw new ValidationException(ValidationException::FORM_VALIDATION);
        } catch (DateMalformedStringException) {
            throw new ValidationException(ValidationException::FORM_VALIDATION);
        }
    }
}
