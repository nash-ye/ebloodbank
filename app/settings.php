<?php
/**
 * App Settings
 *
 * @package EBloodBank
 * @since 1.0
 */

use EBloodBank\Main;
use EBloodBank\Role;
use EBloodBank\Roles;
use EBloodBank\Options;

/*** Default Options **********************************************************/

Options::addOption('genders', array(
    'male'   => __('Male'),
    'female' => __('Female'),
));

Options::addOption('blood_groups', array(
    'A+',
    'A-',
    'B+',
    'B+',
    'O+',
    'O-',
    'AB+',
    'AB-'
));

Options::addOption('entities_per_page', 5);

Options::addOption('self_registration', true);

Options::addOption('default_role', 'subscriber');

/*** Default Roles ************************************************************/

Roles::addRole(new Role('subscriber', __('Subscriber'), array(

    // Donors
    'view_donors'           => true,

    // Cities
    'view_cities'           => true,

    // Districts
    'view_districts'        => true,

) ) );

Roles::addRole(new Role('contributor', __('Contributor'), array(

    // Donors
    'add_donor'             => true,
    'view_donors'           => true,

    // Cities
    'view_cities'           => true,

    // Districts
    'view_districts'        => true,

) ) );

Roles::addRole(new Role('editor', __('Editor'), array(

	// Users
	'view_users'            => true,

	// Donors
	'add_donor'             => true,
	'edit_donor'            => true,
	'edit_donors'           => true,
	'view_donors'           => true,
	'delete_donor'          => true,
	'approve_donor'         => true,

	// Cities
	'add_city'              => true,
	'edit_city'             => true,
	'edit_cities'           => true,
	'view_cities'           => true,
	'delete_city'           => true,

	// Districts
	'add_district'          => true,
	'edit_district'         => true,
	'edit_districts'        => true,
	'view_districts'        => true,
	'delete_district'       => true,

) ) );

Roles::addRole(new Role('administrator', __('Administrator'), array(

	// Users
	'add_user'              => true,
	'edit_user'             => true,
	'edit_users'            => true,
	'view_users'            => true,
	'delete_user'           => true,
	'activate_user'         => true,

	// Donors
	'add_donor'             => true,
	'edit_donor'            => true,
	'edit_donors'           => true,
	'view_donors'           => true,
	'delete_donor'          => true,
	'approve_donor'         => true,

	// Cities
	'add_city'              => true,
	'edit_city'             => true,
	'edit_cities'           => true,
	'view_cities'           => true,
	'delete_city'           => true,

	// Districts
	'add_district'          => true,
	'edit_district'         => true,
	'edit_districts'        => true,
	'view_districts'        => true,
	'delete_district'       => true,

) ) );

/*** User Session *************************************************************/

if (session_status() === PHP_SESSION_NONE) {

    session_set_cookie_params(
            3600,
            parse_url(getHomeURL(), PHP_URL_PATH),
            parse_url(getHomeURL(), PHP_URL_HOST),
            isHTTPS(),
            true
    );

    session_name('EBB_SESSION_ID');

	session_start();

}

/**
 * return the true EBloodBank main instance.
 *
 * @return EBloodBank\Main
 * @since 1.0
 */
function main()
{
    return Main::getInstance();
}

// Fire it up.
main();
