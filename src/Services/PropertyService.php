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

    public function update(array $data): void
    {
        $property = $this->propertyRepo->findById($data['id']);
        $property->address = $data['address'];
        $property->city = $data['city'];
        $property->postalCode = $data['postalCode'];
        $property->country = $data['country'];
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