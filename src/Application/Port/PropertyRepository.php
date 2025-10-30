<?php

namespace Application\Port;

use Domain\Entity\Property;
use Domain\ValueObject\PropertyId;

/**
 *
 */
interface PropertyRepository
{
    /**
     * @param PropertyId $id
     *
     * @return Property
     */
    public function findById(PropertyId $id): Property;

    /**
     * @return Property[]
     */
    public function findAll(): array;

    /**
     * @param Property $property
     *
     * @return void
     */
    public function save(Property $property): void;

    /**
     * @param PropertyId $id
     *
     * @return void
     */
    public function delete(PropertyId $id): void;
}