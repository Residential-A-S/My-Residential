<?php

namespace Application\Dto\Command;

final readonly class PaymentCreateCommand implements CommandInterface
{
    public function __construct(
        public string $amount,
        public string $currency,
        public string $dueAt,
        public string $paidAt,
    ) {
    }
}
