<?php

namespace Adapter\Dto\Command;

final readonly class OrganizationCreateCommand
{
    public function __construct(
        public string $name
    ) {
    }
}
