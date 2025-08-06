<?php

namespace src\Factories;

use src\Models\Payment;

final readonly class PaymentFactory
{
    public function withId(Payment $payment, int $id): Payment
    {
        return new Payment(
            $id,
            $payment->amount,
            $payment->currency,
            $payment->createdAt,
            $payment->dueAt,
            $payment->paidAt,
        );
    }
}
