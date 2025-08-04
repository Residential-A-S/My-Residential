<?php

namespace src\Services;

use src\Exceptions\OrganizationException;
use src\Exceptions\RoleException;
use src\Exceptions\ServerException;
use src\Factories\OrganizationFactory;
use src\Repositories\OrganizationRepository;
use src\Repositories\RoleRepository;
use src\Services\UserOrganizationService;

final readonly class OrganizationService
{
    public function __construct(
        private OrganizationRepository $orgRepo,
        private UserOrganizationService $usrOrgRelService,
        private RoleRepository $roleRepo,
        private AuthService $authService,
        private OrganizationFactory $orgFactory,
    ){}

    /**
     * @throws OrganizationException
     * @throws ServerException
     * @throws RoleException
     */
    public function create(string $name): void
    {
        $user = $this->authService->requireUser();
        $org = $this->orgRepo->create($name);
        $adminRole = $this->roleRepo->findByName("Admin");
        $this->usrOrgRelService->addUserWithoutChecks($user->id, $org->id, $adminRole->id);
    }

    /**
     * @throws OrganizationException
     * @throws ServerException
     */
    public function update(int $id, string $name): void
    {
        $user = $this->authService->requireUser();
        if(!$this->usrOrgRelRepo->isUserAdminInOrganization($user->id, $id)) {
            throw new OrganizationException(OrganizationException::UPDATE_NOT_ALLOWED);
        }
        $org = $this->orgRepo->findById($id);
        $updatedOrg = $this->orgFactory->withUpdatedName($org, $name);
        $this->orgRepo->update($updatedOrg);
    }

    /**
     * @throws OrganizationException
     */
    public function delete(int $id): void
    {
        $user = $this->authService->requireUser();
        if(!$this->usrOrgRelRepo->isUserAdminInOrganization($user->id, $id)) {
            throw new OrganizationException(OrganizationException::DELETE_NOT_ALLOWED);
        }
        $this->orgRepo->delete($id);
    }
}