<?php

use core\Database;
use models\User;

function db(): Database
{
    return Database::getInstance();
}

/**
 * @param string $string
 *
 * @return string
 */
function __(string $string): string
{
    return LOCALIZATION->translate($string);
}

function _e(string $string): void
{
    echo LOCALIZATION->translate($string);
}


/**
 * @return User|null
 * This function retrieves the currently logged-in user based on the session token.
 * If the session token is set, it fetches the user associated with that token.
 * If the token is not set, it returns null.
 */
function p_get_current_user(): ?User
{
    if (isset($_SESSION['token'])) {
        return User::getUserByToken($_SESSION['token']);
    } else {
        return null;
    }
}

/**
 * @return bool
 * This function checks if a user is currently logged in by verifying if there is a valid session token.
 * It returns true if the user is logged in, otherwise false.
 */
function is_logged_in(): bool
{
    return p_get_current_user() !== null;
}
