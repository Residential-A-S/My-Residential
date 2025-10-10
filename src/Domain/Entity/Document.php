<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\Types\DocumentType;
use Domain\ValueObject\DocumentId;

final readonly class Document
{
    public function __construct(
        public DocumentId $id,
        public string $fileName,
        public string $filePath,
        public string $fileType,
        public DateTimeImmutable $uploadedAt,
        public DocumentType $documentType,
    ) {
    }
}