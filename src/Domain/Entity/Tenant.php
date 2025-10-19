<?php

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\Email;
use Domain\ValueObject\Phone;
use Domain\ValueObject\TenantId;

final readonly class Tenant
{
    public function __construct(
        public TenantId $id,
        public string $firstName,
        public string $lastName,
        public Email $email,
        public Phone $phone,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }

    public function rename(string $newFirstName, string $newLastName): self
    {
        return new self(
            id: $this->id,
            firstName: $newFirstName,
            lastName: $newLastName,
            email: $this->email,
            phone: $this->phone,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function updateContact(Email $newEmail, Phone $newPhone): self
    {
        return new self(
            id: $this->id,
            firstName: $this->firstName,
            lastName: $this->lastName,
            email: $newEmail,
            phone: $newPhone,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
