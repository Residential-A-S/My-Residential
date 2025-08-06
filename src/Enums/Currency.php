<?php

namespace src\Enums;

enum Currency: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case GBP = 'GBP';
    case DKK = 'DKK';


    public function getSymbol(): string
    {
        return match ($this) {
            self::EUR => '€',
            self::USD => '$',
            self::GBP => '£',
            self::DKK => 'kr',
        };
    }
}
