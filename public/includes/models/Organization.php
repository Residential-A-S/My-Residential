<?php

namespace models;

use core\Validate;

class Organization
{
    public int $id {
        get => $this->id;
        set => $this->id = $value;
    }
    public string $name {
        get => $this->name;
        set => $this->name = $value;
    }
    public string $description {
        get => $this->description;
        set => $this->description = $value;
    }
    public array $properties {
        get => $this->properties;
        set => $this->properties = $value;
    }
    public array $users {
        get => $this->users;
        set => $this->users = $value;
    }
    public function __construct(int $id, string $name, string $description, string $created_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->created_at = $created_at;
    }

    public static function create(string $name, string $description): bool
    {
        if (!Validate::string($name) || !Validate::string($description)) {
            return false; // Invalid input format
        }

        $organization_id = db()->insert("organization", [
            "name"    => $name,
            "description" => $description
        ]);

        if (is_int($organization_id)) {
            return true;
        }

        return false;
    }

    public function delete(): bool
    {
        $result = db()->delete("organization", ["id" => $this->id]);
        if ($result) {
            return true;
        }

        return false;
    }

    public function addProperty(int $property_id): bool
    {
        $result = db()->insert("organization_property_relations", [
            "organization_id" => $this->id,
            "property_id" => $property_id
        ]);

        return is_int($result);
    }

    public function addUser(int $user_id): bool
    {
        $result = db()->insert("user_organization_relations", [
            "user_id" => $user_id,
            "organization_id" => $this->id
        ]);

        return is_int($result);
    }
}
