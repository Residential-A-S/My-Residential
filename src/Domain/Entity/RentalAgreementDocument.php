<?php

namespace Domain\Entity;

use Domain\Types\DocumentType;
use Domain\ValueObject\DocumentId;
use Domain\ValueObject\RentalAgreementDocumentId;
use Domain\ValueObject\RentalAgreementId;

final readonly class RentalAgreementDocument
{
    public function __construct(
        public RentalAgreementDocumentId $id,
        public RentalAgreementId $rentalAgreementId,
        public DocumentId $documentId,
        public DocumentType $documentType,
    ) {
    }
}
