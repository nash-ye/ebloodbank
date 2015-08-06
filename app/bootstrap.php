<?php
namespace EBloodBank;

/*** Constants ****************************************************************/

const CODENAME = 'EBloodBank';
const VERSION = '1.0-alpha-3';

/*** Configurations ***********************************************************/

if (file_exists(__DIR__ . '/config.php')) {
	require_once __DIR__ . '/config.php';
} else {
	die('Error: The configuration file is not exist.');
}

/*** Default Configurations ***************************************************/

if (! defined('DEBUG_MODE') ) {
	define('DEBUG_MODE', false);
}

if (! defined('ABSPATH') ) {
    define('ABSPATH', dirname(__DIR__));
}

if (! ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

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

/*** Load Helpers *************************************************************/

require ABSPATH . '/app/src/Helpers/l10n.php';
require ABSPATH . '/app/src/Helpers/urls.php';
require ABSPATH . '/app/src/Helpers/html.php';
require ABSPATH . '/app/src/Helpers/helpers.php';
require ABSPATH . '/app/src/Helpers/session.php';
require ABSPATH . '/app/src/Helpers/capabilities.php';

/*** Composer Autoloader ******************************************************/

require ABSPATH . '/app/vendor/autoload.php';

/*** Aura Autoloader **********************************************************/

$loader = new \Aura\Autoload\Loader;
$loader->register();

$loader->addPrefix('EBloodBank', ABSPATH . '/app/src/');

/*** Sessions *****************************************************************/

if ( session_status() !== PHP_SESSION_ACTIVE ) {
	session_start();
}

/*** Roles ********************************************************************/

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
	'view_donors'           => true,
	'delete_donor'          => true,
	'approve_donor'         => true,
	'manage_donors'         => true,

	// Cities
	'add_city'              => true,
	'edit_city'             => true,
	'view_cities'           => true,
	'delete_city'           => true,
	'manage_cities'         => true,

	// Districts
	'add_district'          => true,
	'edit_district'         => true,
	'view_districts'        => true,
	'delete_district'       => true,
	'manage_districts'      => true,

) ) );

Kernal\Roles::addRole( new Kernal\Role( 'administrator', __('Administrator'), array(

	// Users
	'add_user'              => true,
	'edit_user'             => true,
	'view_users'            => true,
	'delete_user'           => true,
	'approve_user'          => true,
	'manage_users'          => true,

	// Donors
	'add_donor'             => true,
	'edit_donor'            => true,
	'view_donors'           => true,
	'delete_donor'          => true,
	'approve_donor'         => true,
	'manage_donors'         => true,

	// Cities
	'add_city'              => true,
	'edit_city'             => true,
	'view_cities'           => true,
	'delete_city'           => true,
	'manage_cities'         => true,

	// Districts
	'add_district'          => true,
	'edit_district'         => true,
	'view_districts'        => true,
	'delete_district'       => true,
	'manage_districts'      => true,

) ) );

/*** Options *******************************************************************/

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
