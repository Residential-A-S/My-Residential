<?php

namespace src\Forms;

use DateMalformedStringException;
use DateTimeImmutable;
use src\Types\Currency;
use src\Types\RouteName;
use Adapter\Http\Exception\ValidationException;
use src\Validation\IntegerRule;
use src\Validation\NumberRule;
use src\Validation\RequiredRule;
use ValueError;

class UpdatePaymentForm extends AbstractForm
{
    public int $paymentId;
    public int $amount;
    public Currency $currency;
    public DateTimeImmutable $dueAt;
    public ?DateTimeImmutable $paidAt;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Payment_Update);

        $this
            ->addField('payment_id', [new RequiredRule(), new IntegerRule()])
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
            $this->paymentId = (int)$input['payment_id'];
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
