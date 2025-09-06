<?php

namespace src\Providers;

use PDO;
use src\Core\Container;

final readonly class DatabaseProvider implements ProviderInterface
{
    public function __construct(
        private array $config,
    ) {
    }
    public function register(Container $c): void
    {
        $c->singleton(PDO::class, function () {
            return new PDO(
                "mysql:dbname=" . $this->config['name'] . ";host=" . $this->config['host'],
                $this->config['user'],
                $this->config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        });
    }
}
