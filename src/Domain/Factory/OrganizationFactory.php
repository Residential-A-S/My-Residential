<?php

namespace Domain\Factory;

use Domain\Entity\Organization;

final readonly class OrganizationFactory
{
    public function withId(Organization $org, int $id): Organization
    {
        return new Organization(
            $id,
            $org->name,
            $org->createdAt,
            $org->updatedAt
        );
    }
}
