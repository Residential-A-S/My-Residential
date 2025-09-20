<?php

namespace Adapter\Http\Controllers\Web;

use Adapter\Http\Response;
use Application\Exception\AuthenticationException;
use Application\Service\AuthenticationService;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class HomeController
{
    public function __construct(
        private Environment $twig,
        private AuthenticationService $authService,
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
