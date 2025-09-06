<?php

namespace src\Core;

final class Container
{
    private array $factories = [];
    private array $singletons = [];
    public function bind(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }
    public function singleton(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
        $this->singletons[$id] = null;
    }
    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->singletons)) {
            return $this->singletons[$id] ??= ($this->factories[$id])($this);
        }
        return ($this->factories[$id])($this);
    }
}
