<?php

namespace Application\Port;

use Domain\Entity\RentalAgreement;
use Domain\ValueObject\RentalAgreementId;

/**
 *
 */
interface RentalAgreementRepository
{
    /**
     * @param RentalAgreementId $id
     *
     * @return RentalAgreement
     */
    public function findById(RentalAgreementId $id): RentalAgreement;

    /**
     * @return RentalAgreement[]
     */
    public function findAll(): array;

    /**
     * @param RentalAgreement $rentalAgreement
     *
     * @return void
     */
    public function save(RentalAgreement $rentalAgreement): void;

    /**
     * @param RentalAgreementId $id
     *
     * @return void
     */
    public function delete(RentalAgreementId $id): void;
}