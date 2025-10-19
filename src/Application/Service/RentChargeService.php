<?php

namespace Application\Service;

use Adapter\Persistence\PdoRentChargeRepository;
use Application\Exception\AuthenticationException;
use DateTimeImmutable;
use Domain\Entity\RentCharge;
use Domain\Exception\PaymentException;
use Domain\Exception\RentalAgreementException;
use Domain\Types\Currency;
use Shared\Exception\ServerException;

final readonly class RentChargeService
{
    public function __construct(
        private PdoRentChargeRepository $rentalAgreementPaymentRepository,
        private PaymentService $paymentService,
        private AuthenticationService $authS,
    ) {
    }

    /**
     * @throws RentalAgreementException
     * @throws ServerException
     * @throws AuthenticationException
     * @throws PaymentException
     */
    public function create(
        int $rentalAgreementId,
        DateTimeImmutable $periodStart,
        DateTimeImmutable $periodEnd,
        float $amount,
        Currency $currency,
        DateTimeImmutable $dueAt,
        DateTimeImmutable $paidAt
    ): void {
        $this->authS->requireUser();
        $payment = $this->paymentService->create(
            amount: $amount,
            currency: $currency,
            dueAt: $dueAt,
            paidAt: $paidAt
        );

        $rentalAgreementPayment = new RentCharge(
            rentalAgreementId: $rentalAgreementId,
            paymentId: $payment->id,
            periodStart: $periodStart,
            periodEnd: $periodEnd,
        );
        $this->rentalAgreementPaymentRepository->create($rentalAgreementPayment);
    }

    /**

     */
    public function update(
        int $rentalAgreementId,
        DateTimeImmutable $periodStart,
        DateTimeImmutable $periodEnd,
        float $amount,
        Currency $currency,
        DateTimeImmutable $dueAt,
        DateTimeImmutable $paidAt
    ): void {
        // Implementation for update method if needed
    }

    /**

     */
    public function delete(int $id): void
    {
        // Implementation for delete method if needed
    }
}
