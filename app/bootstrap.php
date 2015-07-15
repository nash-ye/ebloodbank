<?php
namespace eBloodBank;

/*** Constants ****************************************************************/

define('eBloodBank\VERSION', '1.0-alpha-2');
define('eBloodBank\CODENAME', 'eBloodBank');
define('ABSPATH', dirname(__DIR__));

/*** Configurations ***********************************************************/

if (file_exists(__DIR__ . '/config.php')) {
	require_once __DIR__ . '/config.php';
} else {
	die('Error: The configuration file is not exist.');
}

/*** Error Reporting **********************************************************/

if (! defined('DEBUG_MODE') ) {
	define('DEBUG_MODE', false);
} else {
	if (DEBUG_MODE) {
		error_reporting(E_ALL);
	} else {
		error_reporting(
				E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR |
				E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING |
				E_RECOVERABLE_ERROR
			);
	}
}

/*** Default TimeZone *********************************************************/

if (! ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

/*** Load Helpers *************************************************************/

require ABSPATH . '/app/src/Helpers/l10n.php';
require ABSPATH . '/app/src/Helpers/urls.php';
require ABSPATH . '/app/src/Helpers/html.php';
require ABSPATH . '/app/src/Helpers/helpers.php';
require ABSPATH . '/app/src/Helpers/capabilities.php';

/*** Composer Autoloader ******************************************************/

require ABSPATH . '/app/vendor/autoload.php';

/*** Aura Autoloader **********************************************************/

$loader = new \Aura\Autoload\Loader;
$loader->register();

$loader->addPrefix('eBloodBank', ABSPATH . '/app/src/');

/*** Sessions *****************************************************************/

if ( session_status() !== PHP_SESSION_ACTIVE ) {
	session_start();
}

/*** Roles ********************************************************************/

Kernal\Roles::addRole( new Kernal\Role( 'administrator', __('Administrator'), array(

	// Users
	'add_user'              => true,
	'edit_user'             => true,
	'view_user'             => true,
	'delete_user'           => true,
	'manage_users'          => true,

	// Donors
	'add_donor'             => true,
	'edit_donor'            => true,
	'view_donor'            => true,
	'delete_donor'          => true,
	'approve_donor'         => true,
	'manage_donors'         => true,

	// Cites
	'add_city'              => true,
	'edit_city'             => true,
	'view_city'             => true,
	'delete_city'           => true,
	'manage_cites'          => true,

	// Districts
	'add_distr'             => true,
	'edit_distr'            => true,
	'view_distr'            => true,
	'delete_distr'          => true,
	'manage_distrs'         => true,

) ) );

/*** Options *******************************************************************/


Kernal\Options::set_option('genders', array(
    'male'   => __('Male'),
    'female' => __('Female'),
));

Kernal\Options::set_option('blood_groups', array(
    'A+',
    'A-',
    'B+',
    'B+',
    'O+',
    'O-',
    'AB+',
    'AB-'
));