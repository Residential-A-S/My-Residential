<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\Money;
use Domain\ValueObject\PaymentId;

final readonly class Payment
{
    public function __construct(
        public PaymentId $id,
        public Money $amount,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $dueAt,
        public ?DateTimeImmutable $paidAt,
    ) {
    }

    public function markAsPaid(DateTimeImmutable $paidAt): self
    {
        if ($this->paidAt !== null) {
            return $this;
        }

        return new self(
            id: $this->id,
            amount: $this->amount,
            createdAt: $this->createdAt,
            dueAt: $this->dueAt,
            paidAt: $paidAt,
        );
    }

    public function markAsUnpaid(): self
    {
        if ($this->paidAt === null) {
            return $this;
        }

        return new self(
            id: $this->id,
            amount: $this->amount,
            createdAt: $this->createdAt,
            dueAt: $this->dueAt,
            paidAt: null,
        );
    }

    public function isPaid(): bool
    {
        return $this->paidAt !== null;
    }

    public function isOverdue(DateTimeImmutable $currentDate): bool
    {
        return $this->paidAt === null && $currentDate > $this->dueAt;
    }

    public function daysUntilDue(DateTimeImmutable $currentDate): int
    {
        if ($this->isPaid()) {
            return 0;
        }

        $interval = $currentDate->diff($this->dueAt);
        return (int)$interval->format('%r%a');
    }

    public function extendDueDate(DateTimeImmutable $newDueAt): self
    {
        return new self(
            id: $this->id,
            amount: $this->amount,
            createdAt: $this->createdAt,
            dueAt: $newDueAt,
            paidAt: $this->paidAt,
        );
    }

    public function daysOverdue(DateTimeImmutable $currentDate): int
    {
        if (!$this->isOverdue($currentDate)) {
            return 0;
        }

        $interval = $this->dueAt->diff($currentDate);
        return (int)$interval->format('%a');
    }
}
