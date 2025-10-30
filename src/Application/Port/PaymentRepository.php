<?php

namespace Application\Port;

use Domain\Entity\Payment;
use Domain\ValueObject\PaymentId;

interface PaymentRepository
{
    public function findById(PaymentId $id): Payment;

    /**
     * @return Payment[]
     */
    public function findAll(): array;

    public function save(Payment $payment): void;

    public function delete(PaymentId $id): void;
}