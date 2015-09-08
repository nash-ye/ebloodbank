<?php
/**
 * App Installer
 *
 * @package EBloodBank
 * @since 1.0
 */

/**
 * The absolute path to eBloodBank directory.
 *
 * @var string
 * @since 1.0
 */
define('EBB_DIR', dirname(__FILE__));

/**
 * The absolute URL to eBloodBank directory.
 *
 * @var string
 * @since 1.0
 */
//define('EBB_URL', '');

// Load eBloodBank bootstrapper.
require EBB_DIR . '/app/bootstrap.php';

// Instance an Installer controller.
$installer = new EBloodBank\Controllers\Install();

// Start the Installer!
$installer();
