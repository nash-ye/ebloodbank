<?php
/**
 * Session functions file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

/**
 * @return bool
 * @since 1.0
 */
function isUserLoggedIn()
{
    return (getCurrentUserID() !== 0);
}

/**
 * @return EBloodBank\Models\User
 * @since 1.0
 */
function getCurrentUser()
{
    static $user;
    $userID = 0;

    $session = main()->getSession();
    $segment = $session->getSegment('EBloodBank');
    $userID = (int) $segment->get('user_id', 0);

    if (isValidID($userID) && (! $user || $user->get('id') != $userID)) {
        $em = main()->getEntityManager();
        $user = $em->find('Entities:User', $userID);
    }

    return $user;
}

/**
 * @return int
 * @since 1.0
 */
function getCurrentUserID()
{
    $userID = 0;
    $user = getCurrentUser();

    if (! empty($user)) {
        $userID = (int) $user->get('id');
    }

    return $userID;
}
