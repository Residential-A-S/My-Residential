<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\OrganizationId;
use Domain\ValueObject\PropertyId;

final readonly class Property
{
    public function __construct(
        public PropertyId $id,
        public OrganizationId $organizationId,
        public string $streetName,
        public string $streetNumber,
        public string $zipCode,
        public string $city,
        public string $country,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }
}
