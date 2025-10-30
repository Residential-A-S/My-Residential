<?php

namespace Application\Dto\Command;

final readonly class PaymentDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $paymentId
    ) {
    }
}
