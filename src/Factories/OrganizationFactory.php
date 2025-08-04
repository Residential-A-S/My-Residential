<?php

namespace src\Factories;

use src\Models\Organization;

final readonly class OrganizationFactory
{
    public function withUpdatedName(Organization $org, string $name): Organization
    {
        return new Organization($org->id, $name, $org->createdAt);
    }

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