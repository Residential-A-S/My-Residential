<?php

namespace src\Forms;

use DateMalformedStringException;
use DateTimeImmutable;
use src\Types\Currency;
use src\Types\PaymentInterval;
use src\Types\RouteName;
use Adapter\Http\Exception\ValidationException;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;
use ValueError;

class CreateRentalAgreementPaymentForm extends AbstractForm
{
    public int $rentalAgreementId;
    public DateTimeImmutable $periodStart;
    public ?DateTimeImmutable $periodEnd;
    public float $amount;
    public Currency $currency;
    public DateTimeImmutable $dueAt;
    public ?DateTimeImmutable $paidAt;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Rental_Agreement_Create);

        $this
            ->addField('rental_agreement_id', [new RequiredRule(), new IntegerRule()])
            ->addField('period_start', [new RequiredRule()])
            ->addField('period_end')
            ->addField('amount', [new RequiredRule(), new IntegerRule()])
            ->addField('currency', [new RequiredRule()])
            ->addField('due_at', [new RequiredRule()])
            ->addField('paid_at');
    }

    public function handle(array $input): void
    {
        try {
            parent::handle($input);
            //Write validated data to properties
            $this->rentalAgreementId    = (int)$this->data['rental_agreement_id'];
            $this->periodStart         = new DateTimeImmutable($this->data['period_start']);
            $this->periodEnd           = isset($this->data['period_end']) ?
                new DateTimeImmutable($this->data['period_end']) : null;
            $this->amount              = (float)$this->data['amount'];
            $this->currency            = Currency::from($this->data['currency']);
            $this->dueAt               = new DateTimeImmutable($this->data['due_at']);
            $this->paidAt              = isset($this->data['paid_at']) ?
                new DateTimeImmutable($this->data['paid_at']) : null;
        } catch (ValueError) {
            $this->errors['payment_interval'] = 'Invalid payment interval value';
            throw new ValidationException(ValidationException::FORM_VALIDATION);
        } catch (DateMalformedStringException) {
            throw new ValidationException(ValidationException::FORM_VALIDATION);
        }
    }
}
