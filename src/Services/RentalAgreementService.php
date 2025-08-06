<?php

namespace src\Services;

use DateTimeImmutable;
use src\Enums\PaymentInterval;
use src\Exceptions\AuthenticationException;
use src\Exceptions\RentalAgreementException;
use src\Exceptions\ServerException;
use src\Models\RentalAgreement;
use src\Repositories\RentalAgreementRepository;

final readonly class RentalAgreementService
{
    public function __construct(
        private RentalAgreementRepository $raR,
        private AuthService $authS,
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
