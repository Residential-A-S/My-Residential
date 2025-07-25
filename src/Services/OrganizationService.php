<?php

namespace src\Services;

use src\Models\User;
use src\Repositories\OrganizationRepository;

final readonly class OrganizationService
{
    public function __construct(
        private OrganizationRepository $organizationRepo
    ){}

    public function getCurrentUserOrganizations(User $user): array
    {
        return $this->organizationRepo->findAllForCurrentUser($user->id);
    }

    public function createOrganization(string $name, string $description, User $user): void
    {
        $this->organizationRepo->create($name, $description, $user->id);
    }

    public function updateOrganization(array $data): void
    {
        $org = $this->organizationRepo->findById($data['id']);
        $org->name = $data['name'];
        $org->description = $data['description'];
        $this->organizationRepo->update($org);
    }

    public function deleteOrganization(int $id): void
    {
        $this->organizationRepo->delete($id);
    }
}