<?php

namespace Application\Service;

use Adapter\Persistence\Pdo\OrganizationRepository;
use Application\Exception\AuthenticationException;
use DateTimeImmutable;
use Domain\Exception\OrganizationException;
use PDO;
use Shared\Exception\BaseException;
use Shared\Exception\ServerException;
use src\Entity\Organization;
use src\Types\Permission;
use src\Types\Role;

final readonly class OrganizationService
{
    public function __construct(
        private OrganizationRepository $orgR,
        private AuthenticationService $authS,
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
        $this->orgR->delete($org->id);
    }
}
