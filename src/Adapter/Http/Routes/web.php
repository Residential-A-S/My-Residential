<?php

use Adapter\Http\Controllers\Web\HomeController;
use Adapter\Bootstrap\Container;
use Adapter\Http\Router;
use src\Types\RouteName;

return static function (Router $r, Container $c): void {
    $r->map(RouteName::Web_Home, [$c->get(HomeController::class), 'show']);
    $r->map(RouteName::Web_Login, [$c->get(HomeController::class), 'show']);
};
