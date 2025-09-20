<?php

namespace src\Core;

use src\Core\ResponseException;
use src\Providers\ControllerProvider;
use src\Providers\DatabaseProvider;
use src\Providers\FactoryProvider;
use src\Providers\RepositoryProvider;
use src\Providers\ServiceProvider;
use src\Providers\ViewProvider;

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
