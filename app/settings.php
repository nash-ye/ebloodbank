<?php
/**
 * App Settings
 *
 * @package EBloodBank
 * @since 1.0
 */

use EBloodBank\RouterManager;
use EBloodBank\Kernal\Role;
use EBloodBank\Kernal\Roles;
use EBloodBank\Kernal\Options;

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

/*** Default Roles ************************************************************/

Roles::addRole(new Role('default', __('Default'), array(

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
	'approve_user'          => true,

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

/*** Default Routes ***********************************************************/

RouterManager::add('home', '/');
RouterManager::add('login', '/login/');
RouterManager::add('logout', '/logout/');
RouterManager::add('signup', '/signup/');

RouterManager::add('view-donors', '/donors/');
RouterManager::add('add-donor', '/add/donor/');
RouterManager::add('view-donor', '/donor/{id}/');
RouterManager::add('edit-donors', '/edit/donors/');
RouterManager::add('edit-donor', '/edit/donor/{id}/');

RouterManager::add('view-users', '/users/');
RouterManager::add('add-user', '/add/user/');
RouterManager::add('view-user', '/user/{id}/');
RouterManager::add('edit-users', '/edit/users/');
RouterManager::add('edit-user', '/edit/user/{id}/');

RouterManager::add('view-cities', '/cities/');
RouterManager::add('add-city', '/add/city/');
RouterManager::add('view-city', '/city/{id}/');
RouterManager::add('edit-cities', '/edit/cities/');
RouterManager::add('edit-city', '/edit/city/{id}/');

RouterManager::add('view-districts', '/districts/');
RouterManager::add('add-district', '/add/district/');
RouterManager::add('view-district', '/district/{id}/');
RouterManager::add('edit-districts', '/edit/districts/');
RouterManager::add('edit-district', '/edit/district/{id}/');

RouterManager::match(
    trimTrailingSlash(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) . '/',
    $_SERVER
);

/*** User Session *************************************************************/

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, getHomeURL('relative'));
    session_name('EBB_SESSION_ID');
	session_start();
}
