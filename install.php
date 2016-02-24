<?php
/**
 * eBloodBank installer
 *
 * @package eBloodBank
 * @since   1.0
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
