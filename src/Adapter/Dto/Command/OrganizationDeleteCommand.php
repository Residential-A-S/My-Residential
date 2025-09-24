<?php

namespace Adapter\Dto\Command;

final readonly class OrganizationDeleteCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
