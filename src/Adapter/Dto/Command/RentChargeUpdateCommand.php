<?php

namespace Adapter\Dto\Command;

final readonly class RentChargeUpdateCommand
{
    public function __construct(
        public int $id,
        public int $rentalAgreementId,
        public int $paymentId,
        public string $periodStart,
        public string $periodEnd,
    ) {
    }
}
