<?php

namespace Domain\Factory;

use Domain\Service\Ulid;
use Domain\ValueObject\{DocumentId,
    IssueId,
    OrganizationId,
    PasswordResetId,
    PaymentId,
    PropertyId,
    RentalAgreementId,
    RentalUnitId,
    RentChargeId,
    TenantId,
    UserId};

final readonly class UlidFactory
{
    public function __construct(private Ulid $ulid) {}

    public function documentId(): DocumentId
    {
        return new DocumentId($this->ulid->generate());
    }

    public function issueId(): IssueId
    {
        return new IssueId($this->ulid->generate());
    }

    public function organizationId(): OrganizationId
    {
        return new OrganizationId($this->ulid->generate());
    }

    public function paymentId(): PaymentId
    {
        return new PaymentId($this->ulid->generate());
    }

    public function propertyId(): PropertyId
    {
        return new PropertyId($this->ulid->generate());
    }

    public function rentalAgreementId(): RentalAgreementId
    {
        return new RentalAgreementId($this->ulid->generate());
    }

    public function rentalUnitId(): RentalUnitId
    {
        return new RentalUnitId($this->ulid->generate());
    }

    public function rentChargeId(): RentChargeId
    {
        return new RentChargeId($this->ulid->generate());
    }

    public function tenantId(): TenantId
    {
        return new TenantId($this->ulid->generate());
    }

    public function userId(): UserId
    {
        return new UserId($this->ulid->generate());
    }

    public function passwordResetId(): PasswordResetId
    {
        return new PasswordResetId($this->ulid->generate());
    }
}