<?php

namespace Application\Service;

use Application\Service\PaymentService;
use DateTimeImmutable;
use Application\Service\AuthenticationService;
use src\Types\Currency;
use Application\Exception\AuthenticationException;
use Domain\Exception\PaymentException;
use Domain\Exception\RentalAgreementException;
use Shared\Exception\ServerException;
use src\Entity\RentalAgreementPayment;
use Adapter\Persistence\PdoRentalAgreementPaymentRepository;

final readonly class RentalAgreementPaymentService
{
    public function __construct(
        private PdoRentalAgreementPaymentRepository $rentalAgreementPaymentRepository,
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

        $rentalAgreementPayment = new RentalAgreementPayment(
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
