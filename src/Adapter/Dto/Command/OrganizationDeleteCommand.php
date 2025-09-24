<?php

namespace Adapter\Dto\Command;

final readonly class OrganizationDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
