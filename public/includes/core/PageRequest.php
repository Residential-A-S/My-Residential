<?php

namespace core;

use interfaces\Page;
use interfaces\Requests;
use templates\pages\Dashboard;
use templates\pages\Login;
use templates\pages\Properties;
use templates\pages\Settings;

class PageRequest implements Requests
{
    private array $path;
    private ?Page $page    = null;
    public bool $success = false;

    public function __construct()
    {
        $this->path = explode('/', $_SERVER['REQUEST_URI']);
        foreach ($this->path as $key => $value) {
            if ($value === "") {
                unset($this->path[$key]);
            }
        }
        $this->path = array_values($this->path);
        if (App::isLoggedIn()) {
            if (count($this->path) === 1) { // e.g. /properties
                $this->page = match ($this->path[0]) {
                    'properties' => new Properties(),
                    'settings' => new Settings(),
                    default => new Dashboard(),
                };
            } elseif (count($this->path) === 2) { // e.g. /properties/123
                switch ($this->path[0]) {
                    case 'properties':
                        if (is_numeric($this->path[1])) {
                            $this->page = new PropertiesSingle($this->path[1]);
                        } else {
                            $this->page = new Dashboard();
                        }
                        break;
                    default:
                        $this->page = new Dashboard();
                }
            } else {
                $this->page = new Dashboard();
            }
        } else {
            $this->page = new Login();
        }
        if (!($this->page instanceof Page)) {
            App::setFailed();
        }
    }

    private function headDefaults(): string
    {
        ob_start();
        ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body.unloaded {
                opacity: 0;
                visibility: hidden;
            }

            body {
                opacity: 1;
                visibility: visible;
                transition: opacity 0.3s;
            }
        </style>
        <link rel="stylesheet" href="/assets/style/default.css">
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.body.classList.remove('unloaded');
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public function getHTML(): string
    {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="<?php echo App::$localization->language; ?>">
        <head>
            <title><?php App::_e("__HEADTITLE__") ?></title>
            <?php
            $this->headDefaults();
            $this->page->getHead();
            ?>
        </head>
        <body class="unloaded">
        <?php echo $this->page->getBody() ?>
        <script src="/assets/script/navbar.mjs" type="module"></script>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
