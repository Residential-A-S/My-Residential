<?php

namespace Application\Port;

use Domain\ValueObject\OrganizationId;
use Domain\ValueObject\UserId;

/**
 *
 */
interface UserOrganizationRepository
{
    /**
     * @param UserId $userId
     * @param OrganizationId $organizationId
     *
     * @return void
     */
    public function addUserToOrganization(UserId $userId, OrganizationId $organizationId): void;

    /**
     * @param UserId $userId
     * @param OrganizationId $organizationId
     *
     * @return void
     */
    public function removeUserFromOrganization(UserId $userId, OrganizationId $organizationId): void;

    /**
     * @param UserId $userId
     * @param OrganizationId $organizationId
     *
     * @return bool
     */
    public function userExistsInOrganization(UserId $userId, OrganizationId $organizationId): bool;
}