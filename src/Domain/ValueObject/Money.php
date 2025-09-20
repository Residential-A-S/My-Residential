<?php

namespace Domain\ValueObject;

use src\Types\Currency;
use Domain\Exception\MoneyException;

final readonly class Money
{
    public function __construct(
        public int $minorAmount,
        public Currency $currency,
    ) {
    }

    /**
     * @throws MoneyException
     */
    public function plus(self $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->minorAmount + $other->minorAmount, $this->currency);
    }

    /**
     * @throws MoneyException
     */
    public function minus(self $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->minorAmount - $other->minorAmount, $this->currency);
    }

    /**
     * @throws MoneyException
     */
    public function compare(self $other): int
    {
        $this->assertSameCurrency($other);
        return $this->minorAmount <=> $other->minorAmount;
    }

    /**
     * @throws MoneyException
     */
    private function assertSameCurrency(self $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new MoneyException(MoneyException::ARITHMETIC_ON_DIFFERENT_CURRENCIES);
        }
    }
}