<?php
/**
 * eBloodBank | A Premium Blood Bank System
 *
 * @author    Nashwan Doaqan<nashwan.doaqan@gmail.com>
 * @license   http://www.gnu.org/licenses/gpl.txt GPL-3.0+
 * @copyright (c) 2015, Nashwan Doaqan
 * @version   1.2-alpha-5
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
