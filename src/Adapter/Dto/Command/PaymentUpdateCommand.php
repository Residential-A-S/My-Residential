<?php

namespace Adapter\Dto\Command;


final readonly class PaymentUpdateCommand
{
    public function __construct(
        public int $id,
        public string $amount,
        public string $currency,
        public string $dueAt,
        public string $paidAt,
    ) {
    }
}
