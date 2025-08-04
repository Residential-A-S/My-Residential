<?php

namespace src\Models;

use DateTimeImmutable;
use src\Enums\Currency;

final readonly class Payment
{
    public function __construct(
        public int $id,
        public float $amount,
        public Currency $currency,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $dueAt,
        public DateTimeImmutable $paidAt,
    ) {}
}