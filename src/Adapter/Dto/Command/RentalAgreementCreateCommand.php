<?php

namespace Adapter\Dto\Command;

final readonly class RentalAgreementCreateCommand
{
    public function __construct(
        public int $rentalUnitId,
        public string $startDate,
        public ?string $endDate,
        public string $status,
        public string $paymentInterval
    ) {
    }
}
