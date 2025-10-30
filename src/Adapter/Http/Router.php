<?php

namespace Adapter\Http;

use Adapter\Bootstrap\Container;
use Adapter\Exception\ResponseException;

use function call_user_func;

final class Router
{
    /**
     * @var array<string, array<string, callable>>
     *    ['GET'=>['/foo'=>handler, ...], 'POST'=>[...], ...]
     */
    private array $routes = [];

    /**
     * Register a route.
     *
     * @param RouteName $routeName
     * @param callable $handler A function or [ControllerClass, 'method']
     */
    public function map(RouteName $routeName, callable $handler): void
    {
        $this->routes[strtoupper($routeName->getMethod())][$routeName->getPath()] = $handler;
    }

    /**
     * Dispatch the incoming request to the matching handler.
     *
     * @throws ResponseException
     */
    public function dispatch(Request $request): Response
    {
        $method = strtoupper($request->method);
        $uri    = $request->uri;

        $handler = $this->routes[$method][$uri] ?? null;
        if (!$handler) {
            return Response::json(['error' => 'Not Found'], 404);
        }

        // Handlers can be:
        // 1) an invokable object: new SomeController(...)
        // 2) a [ClassName, 'methodName'] pair
        // In either case, call it with the Request:
        return call_user_func($handler, $request);
    }

    public function load(string $path, Container $c): self
    {
        foreach (glob(rtrim($path, '/') . "/*.php") as $file) {
            $setRoutes = require $path;
            $setRoutes($this, $c);
        }
        return $this;
    }
}
