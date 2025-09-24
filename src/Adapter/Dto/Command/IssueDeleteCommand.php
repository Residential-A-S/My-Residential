<?php

namespace Adapter\Dto\Command;

final readonly class IssueDeleteCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
