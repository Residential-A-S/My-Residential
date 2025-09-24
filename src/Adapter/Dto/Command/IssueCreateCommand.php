<?php

namespace Adapter\Dto\Command;

final readonly class IssueCreateCommand
{
    public function __construct(
        public int $rentalAgreementId,
        public ?int $paymentId,
        public string $name,
        public string $description,
        public string $status
    ) {
    }
}
