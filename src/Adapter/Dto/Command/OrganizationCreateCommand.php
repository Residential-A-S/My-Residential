<?php

namespace Adapter\Dto\Command;

final readonly class OrganizationCreateCommand implements CommandInterface
{
    public function __construct(
        public string $name
    ) {
    }
}
