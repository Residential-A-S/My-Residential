<?php

namespace Adapter\Dto\Command;

final readonly class RentChargeCreateCommand implements CommandInterface
{
    public function __construct(
        public int $rentalAgreementId,
        public int $paymentId,
        public string $periodStart,
        public string $periodEnd,
    ) {
    }
}
