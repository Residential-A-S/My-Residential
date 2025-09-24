<?php

namespace Adapter\Dto\Command;

final readonly class PaymentDeleteCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
