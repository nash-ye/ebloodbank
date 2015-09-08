<?php
/**
 * App Bootstrapper
 *
 * @package EBloodBank
 * @since 1.0
 */

/*** Constants ****************************************************************/

define('EBB_CODENAME', 'EBloodBank');
define('EBB_VERSION', '1.0-alpha-8');

define('EBB_MIN_PHP_VERSION', '5.4');
define('EBB_MIN_MYSQL_VERSION', '5.6');

define('EBB_APP_DIR', dirname(__FILE__));

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

foreach (array('filter', 'session', 'PDO', 'pdo_mysql') as $extension) {

    if (! extension_loaded($extension)) {

        die(
            sprintf(
                'eBloodBank requires the PHP extension %s.',
                $extension
            )
        );

    }

}

/*** User Configurations ******************************************************/

if (file_exists(EBB_APP_DIR . '/config.php')) {
	require_once EBB_APP_DIR . '/config.php';
} else {
	die('Error: The configuration file is not exist.');
}

/*** Error Reporting **********************************************************/

if (defined('EBB_DEV_MODE') && EBB_DEV_MODE) {
    error_reporting(E_ALL);
} else {
    error_reporting(
            E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR |
            E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING |
            E_RECOVERABLE_ERROR
        );
}

/*** Default Configurations ***************************************************/

if (! defined('EBB_DEV_MODE')) {
	define('EBB_DEV_MODE', false);
}

if (! ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

ini_set('filter.default', 'unsafe_raw');
ini_set('filter.default_flags', '');

/*** App Functions ************************************************************/

require EBB_APP_DIR . '/src/EBloodBank/functions.uri.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.http.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.html.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.context.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.session.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.filtering.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.formatting.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.hyperlinks.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.dropdowns.php';

/*** App Autoloader ***********************************************************/

require EBB_APP_DIR . '/vendor/autoload.php';

/*** App Instance ******************************************************/

/**
 * return the true EBloodBank main instance.
 *
 * @return EBloodBank\Main
 * @since 1.0
 */
function main()
{
    return EBloodBank\Main::getInstance();
}

// Fire it up.
main();
