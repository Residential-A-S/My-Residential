<?php

namespace Application\Service;

use DateTimeImmutable;
use Application\Exception\AuthenticationException;
use Domain\Exception\PropertyException;
use Shared\Exception\ServerException;
use src\Entity\Property;
use Adapter\Persistence\PdoPropertyRepository;
use Application\Service\AuthenticationService;

final readonly class PropertyService
{
    public function __construct(
        private PdoPropertyRepository $propR,
        private AuthenticationService $authS,
    ) {
    }

    /**
     * @throws PropertyException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function create(
        int $orgId,
        string $streetName,
        string $streetNumber,
        string $zipCode,
        string $city,
        string $country,
    ): void {
        $this->authS->requireUser();
        $now = new DateTimeImmutable();
        $property = new Property(
            id: 0,
            organizationId: $orgId,
            streetName: $streetName,
            streetNumber: $streetNumber,
            zipCode: $zipCode,
            city: $city,
            country: $country,
            createdAt: $now,
            updatedAt: $now
        );
        $this->propR->create($property);
    }

    /**
     * @throws PropertyException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function update(
        int $id,
        string $streetName,
        string $streetNumber,
        string $zipCode,
        string $city,
        string $country
    ): void {
        $this->authS->requireUser();
        $property = $this->propR->findById($id);
        $updatedProperty = new Property(
            id: $property->id,
            organizationId: $property->organizationId,
            streetName: $streetName,
            streetNumber: $streetNumber,
            zipCode: $zipCode,
            city: $city,
            country: $country,
            createdAt: $property->createdAt,
            updatedAt: new DateTimeImmutable()
        );
        $this->propR->update($updatedProperty);
    }

    /**
     * @throws PropertyException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function delete(int $id): void
    {
        $this->authS->requireUser();
        $property = $this->propR->findById($id);
        $this->propR->delete($property->id);
    }
}
