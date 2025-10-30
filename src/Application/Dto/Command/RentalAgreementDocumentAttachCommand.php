<?php

namespace Application\Dto\Command;

final readonly class RentalAgreementDocumentAttachCommand implements CommandInterface
{
    public function __construct(
        public int $rentalAgreementId,
        public int $documentId,
        public string $documentType,
    ) {
    }
}
