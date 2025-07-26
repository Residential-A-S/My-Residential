<?php

namespace src\Services;

use Exception;
use PDO;
use src\Exceptions\ServerException;
use src\Models\Property;
use src\Models\User;
use src\Repositories\OrganizationRepository;
use src\Repositories\PropertyRepository;
use src\Repositories\RelationRepository;
use Throwable;

final readonly class OrganizationService
{
    public function __construct(
        private OrganizationRepository $organizationRepo,
        private PropertyRepository $propertyRepo,
        private RelationRepository $relationRepo,
        private AuthService $authService,
        private PDO $db
    ){}

    public function getCurrentUserOrganizations(User $user): array
    {
        return $this->organizationRepo->findAllForCurrentUser($user->id);
    }

    public function createOrganization(string $name, User $user): void
    {
        $this->db->beginTransaction();
        try {
            $org = $this->organizationRepo->create($name);
            $this->relationRepo->linkUserOrganization($user->id, $org->id);
            $this->db->commit();
        } catch (Throwable) {
            $this->db->rollBack();
            throw new ServerException(ServerException::ORGANIZATION_CREATE_FAILED);
        }
    }

    public function updateOrganization(array $data): void
    {
        $org = $this->organizationRepo->findById($data['id']);
        $org->name = $data['name'];
        $this->organizationRepo->update($org);
    }

    public function deleteOrganization(int $id): void
    {
        $this->db->beginTransaction();
        $user = $this->authService->requireUser();
        $properties = $this->propertyRepo->findByOrganizationId($id);
        try{
            foreach ($properties as $property) {
                $this->relationRepo->unlinkPropertyOrganization($property->id, $id);
                $this->propertyRepo->delete($property->id);
            }
            $this->relationRepo->unlinkUserOrganization($user->id, $id);
            $this->organizationRepo->delete($id);
            $this->db->commit();
        } catch (Throwable) {
            $this->db->rollBack();
            throw new ServerException(ServerException::ORGANIZATION_DELETE_FAILED);
        }
    }

    public function transferOrganization(int $orgId, int $newUserId): void
    {
        $currentUser = $this->authService->requireUser();
        $this->db->beginTransaction();
        try {
            $this->relationRepo->unlinkUserOrganization($currentUser->id, $orgId);
            $this->relationRepo->linkUserOrganization($newUserId, $orgId);
            $this->db->commit();
        } catch (Throwable) {
            $this->db->rollBack();
            throw new ServerException(ServerException::ORGANIZATION_TRANSFER_FAILED);
        }
    }
}