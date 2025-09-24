<?php

namespace Adapter\Dto\Command;

final readonly class RentalAgreementDocumentUpdateCommand implements CommandInterface
{
    public function __construct(
        public int $id,
        public int $rentalAgreementId,
        public int $documentId,
        public string $documentType,
    ) {
    }
}
