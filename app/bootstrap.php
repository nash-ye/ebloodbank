<?php
namespace EBloodBank;

/*** Constants ****************************************************************/

define('CODENAME', 'EBloodBank');
define('VERSION', '1.0-alpha-5');

/*** User Configurations ******************************************************/

if (file_exists(EBB_DIR . '/app/config.php')) {
	require_once EBB_DIR . '/app/config.php';
} else {
	die('Error: The configuration file is not exist.');
}

/*** Default Configurations ***************************************************/

if (! defined('DEBUG_MODE') ) {
	define('DEBUG_MODE', false);
}

if (! ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

ini_set('filter.default', 'unsafe_raw');
ini_set('filter.default_flags', '');

/*** Error Reporting **********************************************************/

if (DEBUG_MODE) {
    error_reporting(E_ALL);
} else {
    error_reporting(
            E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR |
            E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING |
            E_RECOVERABLE_ERROR
        );
}

/*** App Helpers **************************************************************/

require EBB_DIR . '/app/src/Helpers/uri.php';
require EBB_DIR . '/app/src/Helpers/l10n.php';
require EBB_DIR . '/app/src/Helpers/html.php';
require EBB_DIR . '/app/src/Helpers/general.php';
require EBB_DIR . '/app/src/Helpers/session.php';
require EBB_DIR . '/app/src/Helpers/template.php';

/*** App Packages Autoloader **************************************************/

require EBB_DIR . '/app/vendor/autoload.php';

/*** App Classes Autoloader ***************************************************/

$loader = new \Aura\Autoload\Loader;
$loader->register();

$loader->addPrefix('EBloodBank', EBB_DIR . '/app/src/');

/*** Default Options **********************************************************/

Kernal\Options::addOption('genders', array(
    'male'   => __('Male'),
    'female' => __('Female'),
));

Kernal\Options::addOption('blood_groups', array(
    'A+',
    'A-',
    'B+',
    'B+',
    'O+',
    'O-',
    'AB+',
    'AB-'
));

Kernal\Options::addOption('entities_per_page', 5);

/*** Default Roles ************************************************************/

Kernal\Roles::addRole( new Kernal\Role( 'default', __('Default'), array(

    // Donors
    'add_donor'             => true,
    'view_donors'           => true,

    // Cities
    'view_cities'           => true,

    // Districts
    'view_districts'        => true,

) ) );

Kernal\Roles::addRole( new Kernal\Role( 'editor', __('Editor'), array(

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

Kernal\Roles::addRole( new Kernal\Role( 'administrator', __('Administrator'), array(

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

RouterManager::add('home', '');
RouterManager::add('login', '/login');
RouterManager::add('logout', '/logout');
RouterManager::add('signup', '/signup');

RouterManager::add('view-donors', '/donors');
RouterManager::add('add-donor', '/add/donor');
RouterManager::add('view-donor', '/donor/{id}');
RouterManager::add('edit-donors', '/edit/donors');
RouterManager::add('edit-donor', '/edit/donor/{id}');

RouterManager::add('view-users', '/users');
RouterManager::add('add-user', '/add/user');
RouterManager::add('view-user', '/user/{id}');
RouterManager::add('edit-users', '/edit/users');
RouterManager::add('edit-user', '/edit/user/{id}');

RouterManager::add('view-cities', '/cities');
RouterManager::add('add-city', '/add/city');
RouterManager::add('view-city', '/city/{id}');
RouterManager::add('edit-cities', '/edit/cities');
RouterManager::add('edit-city', '/edit/city/{id}');

RouterManager::add('view-districts', '/districts');
RouterManager::add('add-district', '/add/district');
RouterManager::add('view-district', '/district/{id}');
RouterManager::add('edit-districts', '/edit/districts');
RouterManager::add('edit-district', '/edit/district/{id}');

RouterManager::matchWithCurrentRequest(); // Match the routes with the current request.

/*** User Session *************************************************************/

if ( session_status() !== PHP_SESSION_ACTIVE ) {
	session_start();
}
