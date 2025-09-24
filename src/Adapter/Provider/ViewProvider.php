<?php

namespace Adapter\Provider;

use Adapter\Bootstrap\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final readonly class ViewProvider implements ProviderInterface
{
    public function __construct(
        private array $config,
    ) {
    }
    public function register(Container $c): void
    {
        $c->singleton(Environment::class, function () {
            $loader = new FilesystemLoader($this->config['path']);
            return new Environment($loader, [
                false,//'cache' => __DIR__ . '/../../storage/cache/twig',
                'debug' => true,
            ]);
        });
    }
}
