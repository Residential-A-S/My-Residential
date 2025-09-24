<?php

namespace Adapter\Bootstrap;

use Adapter\Http\Request;
use Adapter\Http\Response;
use Adapter\Http\ResponseException;
use Adapter\Http\Router;
use Adapter\Provider\ControllerProvider;
use Adapter\Provider\DatabaseProvider;
use Adapter\Provider\FactoryProvider;
use Adapter\Provider\RepositoryProvider;
use Adapter\Provider\ServiceProvider;
use Adapter\Provider\ViewProvider;

final readonly class Application
{
    private function __construct(
        private Router $router
    ) {
    }

    public static function bootstrap(Request $request): self
    {
        $c = new Container();

        new DatabaseProvider([
            'host'     => DB_HOST,
            'name'     => DB_NAME,
            'user'     => DB_USER,
            'password' => DB_PASSWORD,
        ])->register($c);

        new ViewProvider([
            'path' => __DIR__ . '/../Views',
        ])->register($c);

        new FactoryProvider()->register($c);
        new RepositoryProvider()->register($c);
        new ServiceProvider([
            'session' => $request->session,
        ])->register($c);
        new ControllerProvider()->register($c);

        $router = new Router()->load(__DIR__ . '/../Routes/', $c);
        return new self($router);
    }

    /**
     * @throws ResponseException
     */
    public function handle(Request $request): Response
    {
        return $this->router->dispatch($request);
    }
}
   