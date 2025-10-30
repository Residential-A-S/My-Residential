<?php

namespace Application\Port;

use Domain\Entity\Organization;
use Domain\ValueObject\OrganizationId;

interface OrganizationRepository
{
    /**
     * Find an CreateOrganizationCommand by its ID.
     * Throws an OrganizationException if the organization is not found.
     *
     * @param OrganizationId $id
     *
     * @return Organization
     */
    public function findById(OrganizationId $id): Organization;

    /**
     * Find all organizations.
     * @return Organization[]
     */
    public function findAll(): array;

    /**
     * Create a new organization.
     */
    public function save(Organization $organization): void;

    /**
     * Delete an organization by its ID.
     * Throws an OrganizationException if the organization is not found.
     * @param OrganizationId $id
     */
    public function delete(OrganizationId $id): void;
}