<?php

namespace Application\Service;

use Adapter\Persistence\Pdo\RentalAgreementRepository;
use Application\Exception\AuthenticationException;
use DateTimeImmutable;
use Domain\Exception\RentalAgreementException;
use Shared\Exception\ServerException;
use src\Entity\RentalAgreement;
use src\Types\PaymentInterval;

final readonly class RentalAgreementService
{
    public function __construct(
        private RentalAgreementRepository $raR,
        private AuthenticationService $authS,
    ) {
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function create(
        int $rentalUnitId,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        string $status,
        PaymentInterval $paymentInterval
    ): void {
        $this->authS->requireUser();
        $now = new DateTimeImmutable();
        $rentalAgreement = new RentalAgreement(
            id: 0,
            rentalUnitId: $rentalUnitId,
            startDate: $startDate,
            endDate: $endDate,
            status: $status,
            paymentInterval: $paymentInterval,
            createdAt: $now,
            updatedAt: $now,
        );
        $this->raR->create($rentalAgreement);
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function update(
        int $id,
        int $rentalUnitId,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        string $status,
        PaymentInterval $paymentInterval
    ): void {
        $this->authS->requireUser();
        $rentalAgreement = $this->raR->findById($id);
        $updatedRentalAgreement = new RentalAgreement(
            id: $rentalAgreement->id,
            rentalUnitId: $rentalUnitId,
            startDate: $startDate,
            endDate: $endDate,
            status: $status,
            paymentInterval: $paymentInterval,
            createdAt: $rentalAgreement->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
        $this->raR->update($updatedRentalAgreement);
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function delete(int $id): void
    {
        $this->authS->requireUser();
        $rentalAgreement = $this->raR->findById($id);
        $this->raR->delete($rentalAgreement->id);
    }
}
