<?php

namespace Adapter\Dto\Command;

final readonly class RentalAgreementUpdateCommand
{
    public function __construct(
        public int $id,
        public int $rentalUnitId,
        public string $startDate,
        public ?string $endDate,
        public string $status,
        public string $paymentInterval
    ) {
    }
}
