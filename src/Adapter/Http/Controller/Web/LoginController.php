<?php

namespace Adapter\Http\Controllers\Web;

use Adapter\Http\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class LoginController
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function show(): Response
    {
        $html = $this->twig->render('login.twig');
        return Response::html($html);
    }
}
