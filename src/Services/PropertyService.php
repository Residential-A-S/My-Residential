<?php

namespace src\Services;

use src\Repositories\PropertyRepository;

final readonly class PropertyService
{
    public function __construct(
        private PropertyRepository $propertyRepo
    ) {}

    public function create(string $country, string $postalCode, string $city, string $address): void
    {
        $this->propertyRepo->create($country, $postalCode, $city, $address);
    }

    public function update(int $id, string $country, string $postalCode, string $city, string $address): void
    {
        $property = $this->propertyRepo->findById($id);
        $property->address = $address;
        $property->city = $city;
        $property->postalCode = $postalCode;
        $property->country = $country;
        $this->propertyRepo->update($property);
    }

    public function delete(int $id): void
    {
        $this->propertyRepo->delete($id);
    }

    public function assignToOrganization(int $propertyId, int $organizationId): void
    {
        if($this->propertyRepo->isAssignedToOrganization($propertyId)) {
            $this->propertyRepo->unassignFromOrganization($propertyId);
        }
        $this->propertyRepo->assignToOrganization($propertyId, $organizationId);
    }
}