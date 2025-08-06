<?php

namespace src\Controllers\Web;

use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Services\AuthService;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class HomeController
{
    public function __construct(
        private Environment $twig,
        private AuthService $authService,
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function show(): Response
    {
        try {
            $this->authService->requireUser();
        } catch (AuthenticationException) {
            return Response::redirect("/login");
        }


        $html = $this->twig->render('home.twig');
        return Response::html($html);
    }
}
