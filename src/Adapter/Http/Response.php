<?php

declare(strict_types=1);

namespace Adapter\Http;

use Adapter\Exception\ResponseException;
use JsonException;

final class Response
{
    private array $headers = [];

    public function __construct(
        private readonly int $statusCode = 200,
        private readonly string $contentType = 'text/html',
        private readonly string $body = ''
    ) {
    }

    /**
     * Add or overwrite a header.
     */
    public function withHeader(string $name, string $value): self
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;
        return $clone;
    }

    /**
     * Create a redirect response.
     */
    public static function redirect(string $url, int $statusCode = 302): self
    {
        return new self($statusCode)->withHeader('Location', $url);
    }

    /**
     * Create a JSON response.
     *
     * @throws ResponseException
     */
    public static function json(mixed $data, int $statusCode = 200): self
    {
        try {
            $body = json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new ResponseException(ResponseException::JSON_ENCODE_FAILED);
        }

        return new self($statusCode, 'application/json', $body)->withHeader('Content-Type', 'application/json');
    }

    /**
     * Create an HTML response.
     */
    public static function html(string $html, int $statusCode = 200): self
    {
        return new self($statusCode, 'text/html', $html);
    }

    /**
     * Emit headers and body to the client.
     */
    public function send(): void
    {
        http_response_code($this->statusCode);
        header("Content-Type: $this->contentType");

        foreach ($this->headers as $name => $value) {
            header("$name: $value", true, $this->statusCode);
        }

        echo $this->body;
    }
}
