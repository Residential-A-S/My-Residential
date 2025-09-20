<?php

declare(strict_types=1);

namespace Adapter\Auth;

use Application\Security\SessionInterface;

/**
 * A native PHP session wrapper implementing SessionInterface.
 */
final class NativeSession implements SessionInterface
{
    /** @var array<string,mixed> */
    private array $session;
    private int $idleTimeout; // 30 minutes

    /**
     * @param array<string,mixed> $session Typically pass in $_SESSION by reference.
     */
    public function __construct(array &$session, int $idleTimeout = 1800)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->session =& $session;
        $this->idleTimeout = $idleTimeout;

        // Enforce expiration based on last activity
        $this->enforceTimeout();

        // Update last-activity timestamp for this request
        $this->updateLastActivity();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->session[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->session[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($this->session[$key]);
    }

    public function all(): array
    {
        return $this->session;
    }

    public function clear(): void
    {
        foreach (array_keys($this->session) as $key) {
            unset($this->session[$key]);
        }
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function updateLastActivity(): void
    {
        $this->session['last_activity'] = time();
    }

    public function getLastActivity(): ?int
    {
        return isset($this->session['last_activity'])
            ? (int) $this->session['last_activity']
            : null;
    }

    public function getIdleTime(): int
    {
        $last = $this->getLastActivity();
        return $last !== null
            ? (time() - $last)
            : PHP_INT_MAX;
    }

    private function enforceTimeout(): void
    {
        if ($this->getLastActivity() !== null && $this->getIdleTime() > $this->idleTimeout) {
            $this->clear();
            $this->regenerate();
        }
    }
}
