<?php

namespace Application\Dto\Command;

final readonly class RentalAgreementDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
