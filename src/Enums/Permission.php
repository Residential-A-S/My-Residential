<?php

namespace src\Enums;

enum Permission: string
{
    case VIEW_ORGANIZATION_USERS = 'view_organization_users';
    case DELETE_ORGANIZATION = 'delete_organization';
    case UPDATE_ORGANIZATION = 'update_organization';
    case CHANGE_USER_ROLE = 'change_user_role';
    case TRANSFER_ORGANIZATION_OWNERSHIP = 'transfer_organization_ownership';
    case MANAGE_USERS_IN_ORGANIZATION = 'manage_users_in_organization';
}
