<?php

namespace Adapter\Dto\Command;

final readonly class TenantDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
