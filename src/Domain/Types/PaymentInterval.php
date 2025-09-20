<?php

namespace Domain\Types;

enum PaymentInterval: string
{
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case SEMI_ANNUALLY = 'semi-annually';
    case ANNUALLY = 'annually';
}
