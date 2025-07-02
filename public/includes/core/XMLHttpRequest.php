<?php

namespace core;

use interfaces\Requests;
use models\User;
use Throwable;

class XMLHttpRequest implements Requests
{
    public bool $success;
    private array $params {
        get {
            return $this->params;
        }
    }
    public string $action {
        get {
            return $this->action;
        }
    }

    public function __construct(array $params = [])
    {
        App::$localization->setFile("XMLRequestMessages");
        $this->params = $params;
        $this->action = $this->params['action'];
        try {
            if (App::isLoggedIn()) {
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
            if (!$this->success) {
                App::setFailed();
            }
        } catch (Throwable) {
            App::setFailed();
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
