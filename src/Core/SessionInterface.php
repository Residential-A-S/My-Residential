<?php

declare(strict_types=1);

namespace src\Core;

/**
 * A simple session abstraction.
 */
interface SessionInterface
{
    /**
     * Get a value from the session.
     *
     * @param string $key
     * @param mixed  $default Returned if the key is not set.
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Set a value in the session.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, mixed $value): void;

    /**
     * Remove a key from the session.
     *
     * @param string $key
     */
    public function remove(string $key): void;

    /**
     * Get all session data.
     *
     * @return array<string,mixed>
     */
    public function all(): array;

    /**
     * Clear the entire session.
     */
    public function clear(): void;

    /**
     * Regenerate the session ID.
     */
    public function regenerate(): void;
}
