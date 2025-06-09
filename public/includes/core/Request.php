<?php

namespace core;

use JetBrains\PhpStorm\NoReturn;

class Request
{
    private string $response_body;
    private bool $is_html;
    private bool $success = true;

    public readonly string $request_method;
    public readonly string $request_uri;
    public readonly array|null $request_parameters;


    public function __construct()
    {
        $this->request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->request_parameters = json_decode(file_get_contents('php://input'), true);
    }

    public function setResponseBody(string $body, bool $is_html): void
    {
        $this->response_body = $body;
        $this->is_html = $is_html;
    }

    public function setFailed(): void
    {
        $this->success = false;
    }


    #[NoReturn] public function respond(): void
    {
        if (!$this->success) {
            http_response_code(404);
        } else {
            http_response_code(200);
        }

        if ($this->is_html) {
            header('Content-Type: text/html; charset=UTF-8');
        } else {
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo $this->response_body;
        exit;
    }
}
