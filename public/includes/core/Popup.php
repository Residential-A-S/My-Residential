<?php

namespace core;

class Popup
{
    private string $name;
    private string $content;

    public function __construct(string $name, mixed $optional = null)
    {
        $this->name = $name;
        $this->content = match ($name) {
            default => null,
        };
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
