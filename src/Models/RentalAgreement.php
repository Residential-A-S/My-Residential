<?php

namespace src\Models;

use DateTimeImmutable;
use src\Enums\PaymentInterval;

final readonly class RentalAgreement
{
    public function __construct(
        public int $id,
        public int $rentalUnitId,
        public DateTimeImmutable $startDate,
        public ?DateTimeImmutable $endDate,
        public string $status,
        public PaymentInterval $paymentInterval,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) { }
}