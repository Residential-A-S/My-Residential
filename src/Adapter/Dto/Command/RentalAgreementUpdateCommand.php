<?php

namespace Adapter\Dto\Command;

final readonly class RentalAgreementUpdateCommand implements CommandInterface
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
