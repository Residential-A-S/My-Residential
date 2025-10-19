<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\OrganizationId;

final readonly class Organization
{
    public function __construct(
        public OrganizationId $id,
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }

    public function rename(string $newName): self
    {
        return new self(
            id: $this->id,
            name: $newName,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }
}
