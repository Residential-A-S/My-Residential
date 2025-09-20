<?php

namespace Application\Service;

use PDO;
use Application\Service\AuthenticationService;
use src\Types\Permission;
use src\Types\Role;
use Application\Exception\AuthenticationException;
use Domain\Exception\OrganizationException;
use Shared\Exception\ServerException;
use Domain\Exception\UserException;
use Adapter\Persistence\PdoOrganizationRepository;
use Adapter\Persistence\UserOrganizationRepository;
use Adapter\Persistence\UserRepository;
use Throwable;

final readonly class UserOrganizationService
{
    public function __construct(
        private UserOrganizationRepository $userOrgRepo,
        private UserRepository $userRepo,
        private PdoOrganizationRepository $orgRepo,
        private AuthenticationService $authService,
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

        // Check if the to-be-added user already exists in the organization
        $this->userRepo->requireById($userId);
        $this->assertNonMembership($userId, $orgId);

        $this->userOrgRepo->addUserToOrganization($userId, $orgId, Role::ADMIN);
    }

    public function addUserWithoutChecks(int $userId, int $orgId, Role $role): void
    {
        $this->userOrgRepo->addUserToOrganization($userId, $orgId, $role);
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

        // Check if the to-be-removed user does not exist in the organization
        $this->userRepo->requireById($userId);
        $this->assertMembership($userId, $orgId, OrganizationException::USER_NOT_IN_ORGANIZATION);

        $this->userOrgRepo->removeUserFromOrganization($userId, $orgId);
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
        if ($this->userOrgRepo->findUserRoleInOrganization($actorId, $orgId) !== Role::OWNER) {
            throw new OrganizationException(OrganizationException::TRANSFER_NOT_ALLOWED);
        }

        // Check if the new owner exists
        $this->userRepo->requireById($newOwnerId);

        // Begin transfer
        $this->db->beginTransaction();
        try {
            // Check if the new owner is already a member of the organization
            if ($this->userOrgRepo->userExistsInOrganization($newOwnerId, $orgId)) {
                $this->userOrgRepo->changeUserRoleInOrganization($newOwnerId, $orgId, Role::OWNER);
            } else {
                $this->userOrgRepo->addUserToOrganization($newOwnerId, $orgId, Role::OWNER);
            }
            $this->userOrgRepo->removeUserFromOrganization($actorId, $orgId);
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
        if (!$this->userOrgRepo->userExistsInOrganization($userId, $orgId)) {
            throw new OrganizationException($exceptionCode);
        }
    }

    /**
     * @throws OrganizationException
     */
    private function assertNonMembership(int $userId, int $orgId): void
    {
        if ($this->userOrgRepo->userExistsInOrganization($userId, $orgId)) {
            throw new OrganizationException(OrganizationException::USER_ALREADY_IN_ORGANIZATION);
        }
    }
}
