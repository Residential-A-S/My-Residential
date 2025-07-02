<?php

/**
 * In this template file the constant 'user' is not defined
 */

namespace templates\pages;

use core\App;
use interfaces\Page;

class Login implements Page
{
    public function __construct()
    {
        App::$localization->setFile("login");
    }

    public function getHead(): string
    {
        ob_start();
        ?>
        <title><?php App::_e("Title") ?></title>
        <link href="/assets/style/login.css" rel="stylesheet">
        <?php
        return ob_get_clean();
    }

    public function getBody(): string
    {
        ob_start();
        ?>
        <form id="loginForm" method="post" data-no-generic="1">
            <input type="email" name="email" placeholder="<?php App::_e("Username") ?>">
            <input type="password" name="password" placeholder="<?php App::_e("Password") ?>">
            <button type="submit"><?php App::_e("Login") ?></button>
            <input type="hidden" name="action" value="login">
        </form>
        <form id="registerForm" method="post" data-no-generic="1">
            <input type="email" name="email" placeholder="<?php App::_e("Username") ?>">
            <input type="password" name="password" placeholder="<?php App::_e("Password") ?>">
            <input type="password" name="repeatPassword" placeholder="<?php App::_e("Password") ?>">
            <button type="submit"><?php App::_e("Register") ?></button>
            <input type="hidden" name="action" value="register">
        </form>
        <script src="/assets/script/login.mjs" type="module"></script>
        <?php
        return ob_get_clean();
    }
}
