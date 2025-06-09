<?php

namespace core;

use interfaces\Requests;
use models\User;
use Throwable;

class XMLHttpRequest implements Requests
{
    public bool $success;
    private array $params;
    private string $action;

    public function __construct()
    {
        $this->params = json_decode(file_get_contents('php://input'), true);
        $this->action = $this->params['action'];
        try {
            if (is_logged_in()) {
                $this->success = match ($this->action) {
                    'logout' => User::logout(),
                    default => false
                };
            } else {
                $this->success = match ($this->action) {
                    'register' => User::register($this->params),
                    'login' => User::login($this->params),
                    default => false
                };
            }
        } catch (Throwable) {
            $this->success = false;
        }
    }

    public function getResponse(): string
    {
        if ($this->success) {
            return '{"success": true}';
        } else {
            return '{"success": false}';
        }
    }
}
