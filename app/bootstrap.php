<?php
/**
 * App Bootstrapper
 *
 * @package EBloodBank
 * @since 1.0
 */

/*** Constants ****************************************************************/

define('EBB_CODENAME', 'EBloodBank');
define('EBB_VERSION', '1.0-alpha-6');

define('EBB_MIN_PHP_VERSION', '5.4');

/*** Requirements **************************************************************/

if (version_compare(PHP_VERSION, EBB_MIN_PHP_VERSION, '<')) {

    die(
        sprintf(
            'eBloodBank requires PHP %s or higher, you are running %s.',
            EBB_MIN_PHP_VERSION,
            PHP_VERSION
        )
    );

}

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

/*** App Packages *************************************************************/

require EBB_DIR . '/app/vendor/autoload.php';

/*** App Autoloader ***********************************************************/

$loader = new Aura\Autoload\Loader;
$loader->register(); // Register the autoloader.
$loader->addPrefix('EBloodBank', EBB_DIR . '/app/src');

/*** App Settings *************************************************************/

require EBB_DIR . '/app/settings.php';
