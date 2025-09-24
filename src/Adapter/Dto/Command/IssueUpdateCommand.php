<?php

namespace Adapter\Dto\Command;

final readonly class IssueUpdateCommand
{
    public function __construct(
        public int $id,
        public int $rentalAgreementId,
        public ?int $paymentId,
        public string $name,
        public string $description,
        public string $status
    ) {
    }
}
