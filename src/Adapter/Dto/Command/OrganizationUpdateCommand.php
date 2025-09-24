<?php

namespace Adapter\Dto\Command;

final readonly class OrganizationUpdateCommand implements CommandInterface
{
    public function __construct(
        public int $organizationId,
        public string $name
    ) {
    }
}
