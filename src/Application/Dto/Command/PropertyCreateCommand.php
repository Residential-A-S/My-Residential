<?php

namespace Application\Dto\Command;

final readonly class PropertyCreateCommand implements CommandInterface
{
    public function __construct(
        public int $organizationId,
        public string $streetName,
        public string $streetNumber,
        public string $zipCode,
        public string $city,
        public string $country
    ) {
    }
}
