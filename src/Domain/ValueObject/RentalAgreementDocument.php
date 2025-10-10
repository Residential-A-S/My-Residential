<?php

namespace Domain\ValueObject;

final readonly class RentalAgreementDocument
{
    public function __construct(
        public RentalAgreementId $rentalAgreementId,
        public DocumentId $documentId,
    ) {
    }
}
