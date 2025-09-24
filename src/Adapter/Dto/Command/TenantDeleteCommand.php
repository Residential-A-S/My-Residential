<?php

namespace Adapter\Dto\Command;

final readonly class TenantDeleteCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
