<?php
declare(strict_types=1);

require_once '../vendor/autoload.php';

use src\Core\Application;
use src\Exceptions\HttpExceptionInterface;
use src\Core\Request;
use src\Core\Response;



try {
    $request  = Request::capture();
    $app = Application::bootstrap($request);
    $response = $app->handle($request);
} catch (HttpExceptionInterface $e) {
    $response = Response::json(
        ['error' => $e->getMessage()],
        $e->getCode()
    );
}

$response->send();