<?php

namespace Adapter\Dto\Command;

final readonly class IssueDeleteCommand
{
    public function __construct(
        public int $id
    ) {
    }
}
