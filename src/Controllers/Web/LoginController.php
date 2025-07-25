<?php

namespace src\Controllers\Web;

use src\Core\Response;
use Twig\Environment;

final readonly class LoginController {

    public function __construct(
        private Environment $twig,
    ) {}

    public function show(): Response
    {
        $html = $this->twig->render('login.twig');
        return Response::html($html);
    }
}