<?php

namespace Adapter\Dto\Command;

final readonly class PaymentCreateCommand
{
    public function __construct(
        public string $amount,
        public string $currency,
        public string $dueAt,
        public string $paidAt,
    ) {
    }
}
