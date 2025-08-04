<?php

namespace src\Enums;

enum Role: string
{
    case OWNER = 'Owner';
    case ADMIN = 'Admin';

    public function getPermissions(): array
    {
        return match ($this) {
            self::OWNER => [
                Permission::VIEW_ORGANIZATION_USERS,
                Permission::DELETE_ORGANIZATION,
                Permission::UPDATE_ORGANIZATION,
                Permission::CHANGE_USER_ROLE,
                Permission::TRANSFER_ORGANIZATION_OWNERSHIP,
                Permission::MANAGE_USERS_IN_ORGANIZATION,
            ],
            self::ADMIN => [
                Permission::VIEW_ORGANIZATION_USERS
            ]
        };
    }
    public function hasPermission(Permission $permission): bool
    {
        return in_array($permission, $this->getPermissions(), true);
    }
}
