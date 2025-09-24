<?php

namespace Adapter\Dto\Command;

final readonly class RentalAgreementDocumentDetachCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
