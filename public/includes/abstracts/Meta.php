<?php

namespace abstract;

abstract class Meta
{
    public function getMetaField(string $field): string|null
    {
        db()->selectSingle("");
        return null;
    }

    public function setMetaField(string $field, string $value): bool
    {
        return false;
    }

    public function deleteMetaField(string $field): bool
    {
        return false;
    }

    public function getMetaFields(): array
    {
        return [];
    }
}
