<?php

namespace traits;

use core\App;
use models\Organization;

trait OrganizationGetters
{
    /**
     * @param int $organization_id
     *
     * @return Organization|null
     */
    public static function getById(int $organization_id): ?Organization
    {
        $org_row = App::$db->selectSingle("organization", "*", [ "ID" => $organization_id ]);
        if ($org_row) {
            return new Organization(
                $org_row["ID"],
                $org_row["name"],
                $org_row["description"],
                $org_row["created_at"]
            );
        }

        return null;
    }
}
