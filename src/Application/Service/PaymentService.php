<?php

namespace Application\Service;

use DateTimeImmutable;
use Application\Service\AuthenticationService;
use src\Types\Currency;
use Application\Exception\AuthenticationException;
use Domain\Exception\PaymentException;
use Shared\Exception\ServerException;
use src\Entity\Payment;
use Adapter\Persistence\PdoPaymentRepository;

final readonly class PaymentService
{
    public function __construct(
        private PdoPaymentRepository $payR,
        private AuthenticationService $authS,
    ) {
    }

    /**
     * @throws PaymentException
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function create(float $amount, Currency $currency, DateTimeImmutable $dueAt, DateTimeImmutable $paidAt): Payment
    {
        $this->authS->requireUser();
        $payment = new Payment(
            id: 0,
            amount: $amount,
            currency: $currency,
            createdAt: new DateTimeImmutable(),
            dueAt: $dueAt,
            paidAt: $paidAt,
        );
        return $this->payR->create($payment);
    }

    /**
     * @throws PaymentException
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function update(
        int $id,
        float $amount,
        Currency $currency,
        DateTimeImmutable $dueAt,
        DateTimeImmutable $paidAt
    ): void {
        $this->authS->requireUser();
        $payment = $this->payR->findById($id);
        $updatedPayment = new Payment(
            id: $payment->id,
            amount: $amount,
            currency: $currency,
            createdAt: $payment->createdAt,
            dueAt: $dueAt,
            paidAt: $paidAt,
        );
        $this->payR->update($updatedPayment);
    }

    /**
     * @throws PaymentException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function delete(int $id): void
    {
        $this->authS->requireUser();
        $payment = $this->payR->findById($id);
        $this->payR->delete($payment->id);
    }
}
