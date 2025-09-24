<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\Money;
use Domain\ValueObject\PaymentId;

final readonly class Payment
{
    public function __construct(
        public PaymentId $id,
        public Money $amount,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $dueAt,
        public DateTimeImmutable $paidAt,
    ) {
    }
}
