<?php

namespace Domain\Factory;

use DateTimeImmutable;
use Domain\Entity\Payment;
use Domain\ValueObject\Money;

final readonly class PaymentFactory
{
    public function __construct(
        private UlidFactory $ulidFactory
    ) {
    }

    public function create(Money $amount, DateTimeImmutable $dueAt, ?DateTimeImmutable $paidAt): Payment
    {
        $now = new DateTimeImmutable();
        return new Payment(
            id: $this->ulidFactory->paymentId(),
            amount: $amount,
            createdAt: $now,
            dueAt: $dueAt,
            paidAt: $paidAt,
        );
    }
}
