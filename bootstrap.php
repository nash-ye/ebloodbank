<?php
/**
 * System bootstrapper
 *
 * @package EBloodBank
 * @since   1.0
 */

/*** Constants ****************************************************************/

define('EBB_CODENAME', 'winry');
define('EBB_VERSION', '1.4');

define('EBB_MIN_PHP_VERSION', '7.2');
define('EBB_MIN_MYSQL_VERSION', '5.7');

/*** Requirements *************************************************************/

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

/*** Paths Constants **********************************************************/

if (! defined('EBB_DIR')) {
    define('EBB_DIR', dirname(__FILE__));
}

define('EBB_LOGS_DIR', EBB_DIR . '/logs');
define('EBB_CACHE_DIR', EBB_DIR . '/cache');
define('EBB_LOCALES_DIR', EBB_DIR . '/locales');
define('EBB_THEMES_DIR', EBB_DIR . '/public/themes');

/*** PHP Configurations *******************************************************/

if (! ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

ini_set('filter.default', 'unsafe_raw');
ini_set('filter.default_flags', '');

/*** User Configurations ******************************************************/

if (file_exists(EBB_DIR . '/config/config.php')) {
    require_once EBB_DIR . '/config/config.php';
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

if (! defined('EBB_DEFAULT_THEME')) {
    define('EBB_DEFAULT_THEME', 'winry');
}

if (! defined('EBB_DEFAULT_LOCALE')) {
    define('EBB_DEFAULT_LOCALE', '');
}

if (! defined('EBB_REDIS_CACHE')) {
    define('EBB_REDIS_CACHE', false);
}

if (! defined('EBB_REDIS_HOST')) {
    define('EBB_REDIS_HOST', 'localhost');
}

if (! defined('EBB_REDIS_PORT')) {
    define('EBB_REDIS_PORT', 6379);
}

if (! defined('EBB_REDIS_DB')) {
    define('EBB_REDIS_DB', '');
}

if (! defined('EBB_REDIS_PASS')) {
    define('EBB_REDIS_PASS', '');
}

if (! defined('EBB_APCU_CACHE')) {
    define('EBB_APCU_CACHE', true);
}

if (! defined('EBB_FS_CACHE')) {
    define('EBB_FS_CACHE', true);
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

require EBB_DIR . '/src/EBloodBank/functions.uri.php';
require EBB_DIR . '/src/EBloodBank/functions.http.php';
require EBB_DIR . '/src/EBloodBank/functions.html.php';
require EBB_DIR . '/src/EBloodBank/functions.context.php';
require EBB_DIR . '/src/EBloodBank/functions.session.php';
require EBB_DIR . '/src/EBloodBank/functions.filtering.php';
require EBB_DIR . '/src/EBloodBank/functions.formatting.php';
require EBB_DIR . '/src/EBloodBank/functions.diagnosing.php';
require EBB_DIR . '/src/EBloodBank/functions.hyperlinks.php';
require EBB_DIR . '/src/EBloodBank/functions.badges.php';
require EBB_DIR . '/src/EBloodBank/functions.fields.php';

/*** App Autoloader ***********************************************************/

require EBB_DIR . '/vendor/autoload.php';

/*** App Instance *************************************************************/

/**
 * The true EBloodBank main instance.
 *
 * @var   EBloodBank\Main
 * @since 1.2
 */
$EBloodBank = EBloodBank\Main::getInstance();
