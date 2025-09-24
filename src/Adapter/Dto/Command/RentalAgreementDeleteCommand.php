<?php

namespace Adapter\Dto\Command;

final readonly class RentalAgreementDeleteCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
