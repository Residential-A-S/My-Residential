<?php

namespace Application\Dto\Command;

final readonly class PropertyUpdateCommand implements CommandInterface
{
    public function __construct(
        public int $id,
        public string $streetName,
        public string $streetNumber,
        public string $zipCode,
        public string $city,
        public string $country
    ) {
    }
}
