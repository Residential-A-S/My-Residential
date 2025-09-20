<?php

namespace src\Forms;

use DateMalformedStringException;
use DateTimeImmutable;
use src\Types\PaymentInterval;
use src\Types\RouteName;
use Adapter\Http\Exception\ValidationException;
use src\Validation\IntegerRule;
use src\Validation\RequiredRule;
use ValueError;

class UpdateRentalAgreementPaymentForm extends AbstractForm
{
    public int $rentalAgreementId;
    public int $rentalUnitId;
    public DateTimeImmutable $startDate;
    public ?DateTimeImmutable $endDate;
    public string $status;
    public PaymentInterval $paymentInterval;

    public function __construct()
    {
        parent::__construct(RouteName::Api_Rental_Agreement_Update);

        $this
            ->addField('rental_agreement_id', [new RequiredRule(), new IntegerRule()])
            ->addField('rental_unit_id', [new RequiredRule(), new IntegerRule()])
            ->addField('start_date', [new RequiredRule()])
            ->addField('end_date')
            ->addField('status', [new RequiredRule()])
            ->addField('payment_interval', [new RequiredRule()]);
    }

    public function handle(array $input): void
    {
        try {
            parent::handle($input);
            //Write validated data to properties
            $this->rentalAgreementId = (int)$this->data['rental_agreement_id'];
            $this->rentalUnitId    = (int)$this->data['rental_unit_id'];
            $this->startDate       = new DateTimeImmutable($this->data['start_date']);
            $this->endDate         = isset($this->data['end_date']) ?
                new DateTimeImmutable($this->data['end_date']) : null;
            $this->status          = $this->data['status'];
            $this->paymentInterval = PaymentInterval::from($this->data['payment_interval']);
        } catch (ValueError) {
            $this->errors['payment_interval'] = 'Invalid payment interval value';
            throw new ValidationException(ValidationException::FORM_VALIDATION);
        } catch (DateMalformedStringException) {
            throw new ValidationException(ValidationException::FORM_VALIDATION);
        }
    }
}
