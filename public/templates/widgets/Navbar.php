<?php

namespace templates\widgets;

class Navbar
{
    public static function topNavbar(): string
    {
        ob_start();
        ?>
        <nav id="topNavbar">

        </nav>
        <?php
        return ob_get_clean();
    }

    public static function sideNavbar(): string
    {
        ob_start();
        ?>
        <nav id="sideNavbar">

        </nav>
        <?php
        return ob_get_clean();
    }
}
