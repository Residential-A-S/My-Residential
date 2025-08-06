<?php

namespace src\Models;

use DateTimeImmutable;

final readonly class RentalAgreementDocument
{
    public function __construct(
        public int $id,
        public int $rentalAgreementId,
        public string $fileName,
        public string $filePath,
        public string $fileType,
        public DateTimeImmutable $uploadedAt
    ) {
    }
}
