<?php

namespace Adapter\Dto\Command;

final readonly class RentalAgreementDocumentDetachCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
