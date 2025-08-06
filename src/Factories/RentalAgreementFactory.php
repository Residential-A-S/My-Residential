<?php

namespace src\Factories;

use src\Models\RentalAgreement;

final readonly class RentalAgreementFactory
{
    public function withId(RentalAgreement $rentalAgreement, int $id): RentalAgreement
    {
        return new RentalAgreement(
            $id,
            $rentalAgreement->rentalUnitId,
            $rentalAgreement->startDate,
            $rentalAgreement->endDate,
            $rentalAgreement->status,
            $rentalAgreement->paymentInterval,
            $rentalAgreement->createdAt,
            $rentalAgreement->updatedAt
        );
    }
}
