<?php

namespace Adapter\Dto\Command;

final readonly class OrganizationUpdateCommand implements CommandInterface
{
    public function __construct(
        public int $id,
        public string $name
    ) {
    }
}
