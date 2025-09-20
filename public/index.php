<?php
declare(strict_types=1);

require_once '../vendor/autoload.php';

use Adapter\Bootstrap\Application;
use Shared\Exception\BaseException;
use Adapter\Http\Request;
use Adapter\Http\Response;



try {
    $request  = Request::capture();
    $app = Application::bootstrap($request);
    $response = $app->handle($request);
} catch (BaseException $e) {
    $response = Response::json(
        ['error' => $e->getMessage()],
        $e->getCode()
    );
}

$response->send();