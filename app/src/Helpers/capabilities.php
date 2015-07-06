<?php

use eBloodBank\Kernal\Role;
use eBloodBank\Kernal\Sessions;

/**
 * @return bool
 * @since 1.0
 */
function isCurrentUserCan($caps, $opt = 'AND')
{
	if (empty($caps) || ! Sessions::isSignedIn()) {
		return false;
	}

	$current_user = Sessions::getCurrentUser();

	if (empty($current_user)) {
		return false;
	}

	return $current_user->hasCaps((array) $caps, $opt);
}

/**
 * @return bool
 * @since 1.0
 */
function isAnonymousCan($caps, $opt = 'AND')
{
    if (empty($caps)) {
        return false;
    }

    $role = new Role( 'anonymous', __('Anonymous'), array(

		// Users
		'view_user'             => true,

		// Donors
		'add_donor'             => true,
		'view_donor'            => true,

		// Cites
		'view_city'             => true,

		// Districts
		'view_distr'            => true,

	) );

    return $role->hasCaps((array) $caps, $opt);
}
