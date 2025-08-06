<?php

namespace src\Models;

use DateTimeImmutable;

final readonly class Issue
{
    public function __construct(
        public int $id,
        public int $rentalAgreementId,
        public ?int $paymentId,
        public string $name,
        public string $description,
        public string $status,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }
}
