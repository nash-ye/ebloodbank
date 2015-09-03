<?php
/**
 * eBloodBank Project | A Premium Blood Bank System.
 *
 * @author Nashwan Doaqan<nashwan.doaqan@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.txt GPL
 * @copyright (c) 2015, Nashwan Doaqan
 * @version 1.0-alpha-7
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

// Sets up eBloodBank settings.
require EBB_DIR . '/app/settings.php';

// Instance a Frontpage controller.
$frontpage = new EBloodBank\Controllers\FrontPage();

// Invoke it!
$frontpage();
