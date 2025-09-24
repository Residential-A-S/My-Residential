<?php

namespace Domain\Types;

enum RentalAgreementStatus: string
{
    case ACTIVE = 'active';
    case TERMINATED = 'terminated';
    case PENDING = 'pending';
}
