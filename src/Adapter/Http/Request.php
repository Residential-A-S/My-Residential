<?php

declare(strict_types=1);

namespace Adapter\Http;

use Adapter\Auth\NativeSession;
use Adapter\Exception\RequestException;
use Application\Security\SessionInterface;
use JsonException;

final readonly class Request
{
    public function __construct(
        public string $method,
        public string $uri,
        public array $query,
        public array $parsedBody,
        public array $headers,
        public array $cookies,
        public array $files,
        public SessionInterface $session,
    ) {
    }

    /**
     * @throws RequestException
     */
    public static function capture(): self
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $method  = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri     = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $query   = $_GET;
        $headers = getallheaders() ?: [];
        $cookies = $_COOKIE;
        $files   = $_FILES;

        // Determine body: JSON if appropriate, otherwise form data
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, '/json')) {
            $raw = (string)@file_get_contents('php://input');
            try {
                $body = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                throw new RequestException(RequestException::INVALID_JSON_FORMAT);
            }
        } else {
            $body = $_POST;
        }

        return new self(
            method:  $method,
            uri:     $uri,
            query:   $query,
            parsedBody:    $body,
            headers: $headers,
            cookies: $cookies,
            files:   $files,
            session: new NativeSession($_SESSION),
        );
    }
}
