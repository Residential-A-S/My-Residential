<?php

use src\Controllers\Web\HomeController;
use src\Core\Container;
use src\Core\Router;
use src\Enums\RouteName;

return static function (Router $r, Container $c): void {
    $r->map(RouteName::Web_Home, [$c->get(HomeController::class), 'show']);
    $r->map(RouteName::Web_Login, [$c->get(HomeController::class), 'show']);
};
