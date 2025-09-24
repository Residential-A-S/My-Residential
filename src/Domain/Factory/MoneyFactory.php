<?php

namespace Domain\Factory;

use Domain\Types\Currency;
use Domain\ValueObject\Money;

final readonly class MoneyFactory
{
    public function fromDecimal(float $amount, Currency $currency, int $precision = 2): Money
    {
        $minorAmount = (int)round($amount * (10 ** $precision));
        return new Money($minorAmount, $currency);
    }
}