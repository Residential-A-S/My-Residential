<?php

namespace Application\Port;

use Domain\Entity\RentalUnit;
use Domain\ValueObject\RentalUnitId;

/**
 *
 */
interface RentalUnitRepository
{
    /**
     * @param RentalUnitId $id
     *
     * @return RentalUnit
     */
    public function findById(RentalUnitId $id): RentalUnit;

    /**
     * @return RentalUnit[]
     */
    public function findAll(): array;

    /**
     * @param RentalUnit $rentalUnit
     *
     * @return void
     */
    public function save(RentalUnit $rentalUnit): void;

    /**
     * @param RentalUnitId $id
     *
     * @return void
     */
    public function delete(RentalUnitId $id): void;
}