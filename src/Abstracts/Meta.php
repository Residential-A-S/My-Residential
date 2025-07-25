<?php

namespace src\Abstracts;

readonly abstract class Meta
{
    abstract public function getField(string $field): string|null;

    abstract public function setField(string $field, string $value): bool;

    abstract public function deleteField(string $field): bool;

    abstract public function getFields(): array;
}
