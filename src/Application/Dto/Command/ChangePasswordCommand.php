<?php

namespace Application\Dto\Command;

final readonly class ChangePasswordCommand implements CommandInterface
{
    public function __construct(
        public string $password
    ) {
    }
}
