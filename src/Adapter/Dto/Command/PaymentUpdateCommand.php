<?php

namespace Adapter\Dto\Command;


final readonly class PaymentUpdateCommand implements CommandInterface
{
    public function __construct(
        public int $paymentId,
        public string $amount,
        public string $currency,
        public string $dueAt,
        public string $paidAt,
    ) {
    }
}
