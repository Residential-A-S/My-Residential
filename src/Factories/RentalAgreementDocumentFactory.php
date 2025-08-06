<?php

namespace src\Factories;

use src\Models\RentalAgreementDocument;

final readonly class RentalAgreementDocumentFactory
{
    public function withId(RentalAgreementDocument $rentalAgreementDocument, int $id): RentalAgreementDocument
    {
        return new RentalAgreementDocument(
            $id,
            $rentalAgreementDocument->rentalAgreementId,
            $rentalAgreementDocument->fileName,
            $rentalAgreementDocument->filePath,
            $rentalAgreementDocument->fileType,
            $rentalAgreementDocument->uploadedAt
        );
    }
}
