<?php

namespace core;

use JetBrains\PhpStorm\NoReturn;
use models\User;
use traits\AppInit;

class App
{
    use AppInit;

    public static ?Database $db = null;
    public static ?Localization $localization = null;
    public static ?User $user = null;
    public static string $root;
    public static string $version = '0.0.1';

    private static string $response_body;
    private static bool $is_html;
    private static bool $success;
    public static string $response_message_code;

    public static string|null $request_method;
    public static string|null $request_uri;
    public static array|null $request_parameters;

    public static function init(): void
    {
        session_start();
        self::$db = Database::getInstance();

        App::createTables();

        self::$localization = new Localization();
        if (isset($_SESSION['token'])) {
            self::$user = User::getByToken($_SESSION['token']);
        }
        self::$root = __DIR__ . '/../../';

        self::$response_body = '';
        self::$is_html = true;
        self::$success = true;
        self::$response_message_code = '';

        self::$request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
        self::$request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS);
        self::$request_parameters = json_decode(file_get_contents('php://input'), true);
    }

    #[NoReturn] public static function run(): void
    {
        if (self::$request_uri === '/xhr') {
            $request = new XMLHttpRequest(json_decode(file_get_contents('php://input'), true));
            self::setResponseBody($request->getResponse(), false);
        } else {
            $request = new PageRequest();
            self::setResponseBody($request->getHTML());
        }
        self::respond();
    }

    #[NoReturn] public static function respond(): void
    {
        if (!self::$success) {
            http_response_code(404);
        } else {
            http_response_code(200);
        }

        if (self::$is_html) {
            header('Content-Type: text/html; charset=UTF-8');
        } else {
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo self::$response_body;
        exit;
    }

    public static function setResponseBody(string $body, bool $is_html = true): void
    {
        self::$response_body = $body;
        self::$is_html = $is_html;
    }

    public static function setFailed(): void
    {
        self::$success = false;
    }

    private function getResponseMessage(): string
    {

        if (!empty(self::$response_message_code)) {
            return self::__(self::$response_message_code);
        }
        return '';
    }

    public static function __(string $string): string
    {
        return self::$localization->translate($string);
    }

    public static function _e(string $string): void
    {
        echo self::__($string);
    }

    public static function isLoggedIn(): bool
    {
        return self::$user !== null;
    }
}
