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
}
