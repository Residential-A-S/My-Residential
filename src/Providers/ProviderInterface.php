<?php

namespace src\Providers;

use src\Core\Container;

interface ProviderInterface
{
    public function register(Container $c): void;
}
