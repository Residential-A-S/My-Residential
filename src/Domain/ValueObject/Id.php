<?php

namespace Domain\ValueObject;

use InvalidArgumentException;

abstract readonly class Id
{
    public function __construct(public int $value)
    {
        if ($this->value <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer.');
        }
    }
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}