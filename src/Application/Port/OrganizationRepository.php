<?php

namespace Application\Port;

use Domain\Exception\OrganizationException;
use src\Entity\Organization;

interface OrganizationRepository
{
    /**
     * Find an CreateOrganizationCommand by its ID.
     * Throws an OrganizationException if the organization is not found.
     * @throws OrganizationException
     */
    public function findById(int $id): Organization;

    /**
     * Find all organizations.
     * @return Organization[]
     */
    public function findAll(): array;

    /**
     * Create a new organization.
     * @throws OrganizationException
     */
    public function save(Organization $organization): Organization;

    /**
     * Delete an organization by its ID.
     * Throws an OrganizationException if the organization is not found.
     * @throws OrganizationException
     */
    public function delete(int $id): void;
}