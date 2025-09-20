<?php

namespace src\Providers;

use Adapter\Bootstrap\Container;

interface ProviderInterface
{
    public function register(Container $c): void;
}
