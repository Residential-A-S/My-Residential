<?php

/**
 * In template files the constant 'user' is defined as the current user
 */

namespace templates\pages;

use interfaces\Page;
use templates\widgets\Navbar;

class Dashboard implements Page
{
    public function __construct()
    {
        LOCALIZATION->setFile("dashboard");
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
        echo Navbar::sideNavbar();
        ?>
        <div class="content-topNavbar">
            <?php echo Navbar::topNavbar() ?>
            <div id="content">
                <?php _e("Title") ?>
            </div>
        </div>
        <script src="/public/assets/assets/script/app.mjs" type="module"></script>
        <?php
        return ob_get_clean();
    }
}
