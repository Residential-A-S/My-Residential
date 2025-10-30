<?php

namespace Application\Service;

use Adapter\Persistence\Pdo\RentalUnitRepository;
use Application\Exception\AuthenticationException;
use DateTimeImmutable;
use Domain\Exception\RentalUnitException;
use Shared\Exception\ServerException;
use src\Entity\RentalUnit;

final readonly class RentalUnitService
{
    public function __construct(
        private RentalUnitRepository $ruR,
        private AuthenticationService $authS,
    ) {
    }

    /**
     * @throws RentalUnitException
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function create(int $propertyId, string $name, string $status): void
    {
        $this->authS->requireUser();
        $now = new DateTimeImmutable();
        $rentalUnit = new RentalUnit(
            id: 0,
            propertyId: $propertyId,
            name: $name,
            status: $status,
            createdAt: $now,
            updatedAt: $now
        );
        $this->ruR->create($rentalUnit);
    }

    /**
     * @throws RentalUnitException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function update(int $id, int $propertyId, string $name, string $status): void
    {
        $this->authS->requireUser();
        $rentalUnit = $this->ruR->findById($id);
        $updatedRentalUnit = new RentalUnit(
            id: $rentalUnit->id,
            propertyId: $propertyId,
            name: $name,
            status: $status,
            createdAt: $rentalUnit->createdAt,
            updatedAt: new DateTimeImmutable()
        );
        $this->ruR->update($updatedRentalUnit);
    }

    /**
     * @throws RentalUnitException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function delete(int $id): void
    {
        $this->authS->requireUser();
        $rentalUnit = $this->ruR->findById($id);
        $this->ruR->delete($rentalUnit->id);
    }
}
