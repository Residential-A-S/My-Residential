<?php

namespace Application\Dto\Command;

final readonly class IssueUpdateCommand implements CommandInterface
{
    public function __construct(
        public int $issueId,
        public int $rentalAgreementId,
        public ?int $paymentId,
        public string $name,
        public string $description,
        public string $status
    ) {
    }
}
