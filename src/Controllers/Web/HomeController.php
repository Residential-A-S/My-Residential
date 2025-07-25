<?php

namespace src\Controllers\Web;

use src\Core\Request;
use src\Core\Response;
use src\Exceptions\AuthenticationException;
use src\Services\AuthService;
use Twig\Environment;

final readonly class HomeController {

    public function __construct(
        private Environment $twig,
        private AuthService $authService,
    ) {}
    public function show(Request $request): Response
    {
        try{
            $this->authService->requireStudent();
        } catch (AuthenticationException){
            return Response::redirect("/login");
        }


        $html = $this->twig->render('home.twig');
        return Response::html($html);
    }
}