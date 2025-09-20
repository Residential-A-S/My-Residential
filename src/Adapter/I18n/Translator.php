<?php

namespace Adapter\I18n;

final readonly class Translator
{
    private array $translations;
    public function __construct(
        string $ctx,
        string $lang = 'da',
    ) {
        $this->translations = $this->load($ctx, $lang);
    }

    private function load(string $ctx, string $lang): array
    {
        return require __DIR__ . "/../Localization/$lang/$ctx.php";
    }

    public function translate(string $key): string
    {
        return $this->translations[$key] ?? $key;
    }
}
