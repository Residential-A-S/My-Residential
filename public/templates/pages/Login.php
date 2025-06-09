<?php

/**
 * In this template file the constant 'user' is not defined
 */

namespace templates\pages;

use interfaces\Page;

class Login implements Page
{
    public function __construct()
    {
        LOCALIZATION->setFile("login");
    }

    public function getHead(): string
    {
        ob_start();
        ?>
        <title><?php _e("Title") ?></title>
        <link href="/public/assets/assets/style/login.css" rel="stylesheet">
        <?php
        return ob_get_clean();
    }

    public function getBody(): string
    {
        ob_start();
        ?>
        <form id="loginForm" method="post" data-no-generic="1">
            <input type="email" name="email" placeholder="<?php _e("Username") ?>">
            <input type="password" name="password" placeholder="<?php _e("Password") ?>">
            <button type="submit"><?php _e("Login") ?></button>
            <input type="hidden" name="action" value="login">
        </form>
        <form id="registerForm" method="post" data-no-generic="1">
            <input type="email" name="email" placeholder="<?php _e("Username") ?>">
            <input type="password" name="password" placeholder="<?php _e("Password") ?>">
            <input type="password" name="repeatPassword" placeholder="<?php _e("Password") ?>">
            <button type="submit"><?php _e("Register") ?></button>
            <input type="hidden" name="action" value="register">
        </form>
        <script src="/public/assets/assets/script/login.mjs" type="module"></script>
        <?php
        return ob_get_clean();
    }
}
