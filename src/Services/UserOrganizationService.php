<?php

namespace src\Services;

use PDO;
use src\Enums\Permission;
use src\Enums\Role;
use src\Exceptions\AuthenticationException;
use src\Exceptions\OrganizationException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Repositories\OrganizationRepository;
use src\Repositories\UserOrganizationRepository;
use src\Repositories\UserRepository;
use src\Services\AuthService;
use Throwable;

final readonly class UserOrganizationService
{
    public function __construct(
        private UserOrganizationRepository $usrOrgRelRepo,
        private UserRepository $userRepo,
        private OrganizationRepository $orgRepo,
        private AuthService $authService,
        private PDO $db
    ) {
    }

    /**
     * @param int $userId
     * @param int $orgId
     * @throws OrganizationException
     * @throws ServerException
     * @throws UserException
     * @throws AuthenticationException
     */
    public function addUserToOrganization(int $userId, int $orgId): void
    {
        // Check if the user is logged in
        $actorId = $this->authService->requireUser()->id;

        // Check if the organization id exists
        $this->orgRepo->requireById($orgId);

        // Check if the current user is a member of the organization
        $this->assertMembership($actorId, $orgId, OrganizationException::ADD_USER_NOT_ALLOWED);

        // Check if the current user is allowed to add users
        if (!$this->authService->canUserPerformAction($actorId, $orgId, Permission::MANAGE_USERS_IN_ORGANIZATION)) {
            throw new OrganizationException(OrganizationException::ADD_USER_NOT_ALLOWED);
        }

        // Check if the to-be-added user already exists in the organization
        $this->userRepo->requireById($userId);
        $this->assertNonMembership($userId, $orgId);

        $this->usrOrgRelRepo->addUserToOrganization($userId, $orgId, Role::ADMIN);
    }

    public function addUserWithoutChecks(int $userId, int $orgId, Role $role): void
    {
        $this->usrOrgRelRepo->addUserToOrganization($userId, $orgId, $role);
    }

    /**
     * @throws OrganizationException
     * @throws UserException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function removeUserFromOrganization(int $userId, int $orgId): void
    {
        // Check if the user is logged in
        $actorId = $this->authService->requireUser()->id;

        // Check if the organization id exists
        $this->orgRepo->requireById($orgId);

        // Check if the current user is a member of the organization
        $this->assertMembership($actorId, $orgId, OrganizationException::REMOVE_USER_NOT_ALLOWED);

        if ($actorId === $userId) {
            throw new OrganizationException(OrganizationException::REMOVE_SELF_NOT_ALLOWED);
        }

        // Check if the current user is allowed to remove users
        if (!$this->authService->canUserPerformAction($actorId, $orgId, Permission::MANAGE_USERS_IN_ORGANIZATION)) {
            throw new OrganizationException(OrganizationException::REMOVE_USER_NOT_ALLOWED);
        }

        // Check if the to-be-removed user does not exist in the organization
        $this->userRepo->requireById($userId);
        $this->assertMembership($userId, $orgId, OrganizationException::USER_NOT_IN_ORGANIZATION);

        $this->usrOrgRelRepo->removeUserFromOrganization($userId, $orgId);
    }

    /**
     * @throws OrganizationException
     * @throws UserException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function transferOwnership(int $newOwnerId, int $orgId): void
    {
        // Check if the user is logged in
        $actorId = $this->authService->requireUser()->id;

        // Check if the organization exists
        $this->orgRepo->requireById($orgId);

        // Check if the current user is a member of the organization
        $this->assertMembership($actorId, $orgId, OrganizationException::TRANSFER_NOT_ALLOWED);

        // Check if the current user is allowed to transfer ownership
        if ($this->usrOrgRelRepo->findUserRoleInOrganization($actorId, $orgId) !== Role::OWNER) {
            throw new OrganizationException(OrganizationException::TRANSFER_NOT_ALLOWED);
        }

        // Check if the new owner exists
        $this->userRepo->requireById($newOwnerId);

        // Begin transfer
        $this->db->beginTransaction();
        try {
            // Check if the new owner is already a member of the organization
            if ($this->usrOrgRelRepo->userExistsInOrganization($newOwnerId, $orgId)) {
                $this->usrOrgRelRepo->changeUserRoleInOrganization($newOwnerId, $orgId, Role::OWNER);
            } else {
                $this->usrOrgRelRepo->addUserToOrganization($newOwnerId, $orgId, Role::OWNER);
            }
            $this->usrOrgRelRepo->removeUserFromOrganization($actorId, $orgId);
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw new ServerException($e);
        }
        $this->db->commit();
    }

    /**
     * @throws OrganizationException
     */
    private function assertMembership(int $userId, int $orgId, int $exceptionCode): void
    {
        if (!$this->usrOrgRelRepo->userExistsInOrganization($userId, $orgId)) {
            throw new OrganizationException($exceptionCode);
        }
    }

    /**
     * @throws OrganizationException
     */
    private function assertNonMembership(int $userId, int $orgId): void
    {
        if ($this->usrOrgRelRepo->userExistsInOrganization($userId, $orgId)) {
            throw new OrganizationException(OrganizationException::USER_ALREADY_IN_ORGANIZATION);
        }
    }
}
