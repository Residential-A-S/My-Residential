<?php

namespace Adapter\Provider;

use Adapter\Bootstrap\Container;

interface ProviderInterface
{
    public function register(Container $c): void;
}
