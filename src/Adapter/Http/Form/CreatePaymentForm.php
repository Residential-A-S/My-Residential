<?php

namespace Adapter\Http\Form;

use DateMalformedStringException;
use DateTimeImmutable;
use Adapter\Http\RouteName;
use Adapter\Http\Exception\ValidationException;
use Adapter\Http\Form\Validation\NumberRule;
use Adapter\Http\Form\Validation\RequiredRule;
use Domain\Types\Currency;
use Domain\ValueObject\Money;
use ValueError;

class CreatePaymentForm extends AbstractForm
{
    public Money $money;
    public DateTimeImmutable $dueAt;
    public ?DateTimeImmutable $paidAt;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Payment_Create);

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
            $amount   = (int)$input['amount'];
            $currency = Currency::from($input['currency']);

            $this->money = new Money($amount, $currency);

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
