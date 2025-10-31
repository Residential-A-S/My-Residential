<?php

namespace Application\Port;

use Domain\Entity\RentCharge;
use Domain\ValueObject\PaymentId;
use Domain\ValueObject\RentalAgreementId;
use Domain\ValueObject\RentChargeId;

/**
 *
 */
interface RentChargeRepository
{
    /**
     * @param RentChargeId $id
     *
     * @return RentCharge
     */
    public function findById(RentChargeId $id): RentCharge;

    /**
     * @param PaymentId $paymentId
     *
     * @return RentCharge
     */
    public function findByPaymentId(PaymentId $paymentId): RentCharge;

    /**
     * @param RentalAgreementId $rentalAgreementId
     *
     * @return RentCharge[]
     */
    public function findByRentalAgreementId(RentalAgreementId $rentalAgreementId): array;

    /**
     * @return RentCharge[]
     */
    public function findAll(): array;

    /**
     * @param RentCharge $rentCharge
     *
     * @return void
     */
    public function save(RentCharge $rentCharge): void;

    /**
     * @param RentChargeId $id
     *
     * @return void
     */
    public function delete(RentChargeId $id): void;
}