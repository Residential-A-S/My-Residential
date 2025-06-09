<?php

/**
 * In template files the constant 'user' is defined as the current user
 */

namespace templates\pages;

use interfaces\Page;

class Settings implements Page
{
    public function __construct()
    {
        LOCALIZATION->setFile("settings");
    }

    public function getHead(): string
    {
        ob_start();
        ?>
        <title><?php _e("Title") ?></title>
        <link href="/public/assets/assets/style/default.css" rel="stylesheet">
        <?php
        return ob_get_clean();
    }

    public function getBody(): string
    {
        ob_start();
        ?>

        <?php
        return ob_get_clean();
    }
}
