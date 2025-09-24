<?php

namespace Adapter\Dto\Command;

final readonly class PaymentDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
