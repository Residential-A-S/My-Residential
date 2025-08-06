<?php

namespace src\Services;

use DateTimeImmutable;
use PDO;
use src\Enums\Permission;
use src\Enums\Role;
use src\Exceptions\AuthenticationException;
use src\Exceptions\BaseException;
use src\Exceptions\OrganizationException;
use src\Exceptions\ServerException;
use src\Models\Organization;
use src\Repositories\OrganizationRepository;

final readonly class OrganizationService
{
    public function __construct(
        private OrganizationRepository $orgR,
        private AuthService $authS,
        private UserOrganizationService $userOrgS,
        private PDO $db
    ) {
    }

    /**
     * @throws OrganizationException
     * @throws BaseException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function create(string $name): void
    {
        $user = $this->authS->requireUser();
        $now = new DateTimeImmutable();
        $org = new Organization(
            id: 0,
            name: $name,
            createdAt: $now,
            updatedAt: $now,
        );
        $this->db->beginTransaction();
        try {
            $org = $this->orgR->create($org);
            $this->userOrgS->addUserWithoutChecks($user->id, $org->id, Role::OWNER);
            $this->db->commit();
        } catch (BaseException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * @throws OrganizationException
     * @throws ServerException
     * @throws AuthenticationException
     */
    public function update(int $id, string $name): void
    {
        $user = $this->authS->requireUser();
        $org = $this->orgR->requireById($id);
        if (!$this->authS->canUserPerformAction($user->id, $org->id, Permission::UPDATE_ORGANIZATION)) {
            throw new OrganizationException(OrganizationException::UPDATE_NOT_ALLOWED);
        }
        $updatedOrg = new Organization(
            id: $org->id,
            name: $name,
            createdAt: $org->createdAt,
            updatedAt: new DateTimeImmutable(),
        );
        $this->orgR->update($updatedOrg);
    }

    /**
     * @throws OrganizationException
     * @throws AuthenticationException
     * @throws ServerException
     */
    public function delete(int $id): void
    {
        $user = $this->authS->requireUser();
        $org = $this->orgR->requireById($id);
        if (!$this->authS->canUserPerformAction($user->id, $org->id, Permission::DELETE_ORGANIZATION)) {
            throw new OrganizationException(OrganizationException::DELETE_NOT_ALLOWED);
        }
        $this->orgR->delete($org->id);
    }
}
