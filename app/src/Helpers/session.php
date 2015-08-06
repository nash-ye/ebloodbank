<?php

use EBloodBank\EntityManager;

/**
 * @return bool
 * @since 1.0
 */
function isUserLoggedIn()
{
    return (getCurrentUserID() !== 0);
}

/**
 * @return Models\User
 * @since 1.0
 */
function getCurrentUser()
{
    if (isUserLoggedIn()) {
        $userID = getCurrentUserID();
        return EntityManager::getUserRepository()->find($userID);
    }
}

/**
 * @return int
 * @since 1.0
 */
function getCurrentUserID()
{
    return (isset($_SESSION['user_id'])) ? (int) $_SESSION['user_id'] : 0;
}
