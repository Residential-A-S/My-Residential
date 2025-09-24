<?php

namespace Adapter\Dto\Command;

final readonly class OrganizationUpdateCommand
{
    public function __construct(
        public int $id,
        public string $name
    ) {
    }
}
