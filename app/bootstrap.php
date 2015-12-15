<?php
/**
 * eBloodBank bootstrapper
 *
 * @package EBloodBank
 * @since   1.0
 */

/*** Constants ****************************************************************/

define('EBB_CODENAME', 'EBloodBank');
define('EBB_VERSION', '1.2.3');

define('EBB_MIN_PHP_VERSION', '5.5');
define('EBB_MIN_MYSQL_VERSION', '5.0');

define('EBB_APP_DIR', dirname(__FILE__));
define('EBB_LOCALES_DIR', EBB_APP_DIR . '/locales');

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

foreach (['spl', 'date', 'filter', 'session'] as $ext) {
    if (! extension_loaded($ext)) {
        die(
            sprintf(
                'eBloodBank requires the PHP extension %s.',
                $ext
            )
        );
    }
}

if (function_exists('apache_get_modules')) {
    if (! in_array('mod_rewrite', apache_get_modules())) {
        die('eBloodBank requires Apache mod_rewrite module.');
    }
}

/*** PHP Configurations *******************************************************/

if (! ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

ini_set('filter.default', 'unsafe_raw');
ini_set('filter.default_flags', '');

/*** User Configurations ******************************************************/

if (file_exists(EBB_APP_DIR . '/config.php')) {
    require_once EBB_APP_DIR . '/config.php';
}

if (! defined('EBB_DB_NAME')) {
    define('EBB_DB_NAME', '');
}

if (! defined('EBB_DB_USER')) {
    define('EBB_DB_USER', '');
}

if (! defined('EBB_DB_PASS')) {
    define('EBB_DB_PASS', '');
}

if (! defined('EBB_DB_HOST')) {
    define('EBB_DB_HOST', 'localhost');
}

if (! defined('EBB_DB_DRIVER')) {
    define('EBB_DB_DRIVER', 'mysqli');
}

if (! defined('EBB_DEFAULT_LOCALE')) {
    define('EBB_DEFAULT_LOCALE', '');
}

if (! defined('EBB_DEV_MODE')) {
    define('EBB_DEV_MODE', false);
}

/*** Error Reporting **********************************************************/

if (EBB_DEV_MODE) {
    error_reporting(E_ALL | E_STRICT);
} else {
    error_reporting(
        E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_CORE_WARNING |
        E_COMPILE_ERROR | E_COMPILE_WARNING |
        E_USER_ERROR | E_USER_WARNING |
        E_RECOVERABLE_ERROR
    );
}

/*** App Functions ************************************************************/

require EBB_APP_DIR . '/src/EBloodBank/functions.uri.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.http.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.html.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.context.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.session.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.filtering.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.formatting.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.diagnosing.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.hyperlinks.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.badges.php';
require EBB_APP_DIR . '/src/EBloodBank/functions.fields.php';

/*** App Autoloader ***********************************************************/

require EBB_APP_DIR . '/vendor/autoload.php';

/*** App Instance *************************************************************/

/**
 * The true EBloodBank main instance.
 *
 * @var EBloodBank\Main
 * @since 1.2
 */
$EBloodBank = EBloodBank\Main::getInstance();
