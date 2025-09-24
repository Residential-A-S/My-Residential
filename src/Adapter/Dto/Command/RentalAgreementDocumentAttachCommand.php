<?php

namespace Adapter\Dto\Command;

final readonly class RentalAgreementDocumentAttachCommand
{
    public function __construct(
        public int $rentalAgreementId,
        public int $documentId,
        public string $documentType,
    ) {
    }
}
