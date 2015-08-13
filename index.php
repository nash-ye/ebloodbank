<?php
/**
 * eBloodBank Project | Because Life Is Worth Living.
 *
 * A system to manage a blood donors database. with additional featues like
 * searching, reviewing, donations log and more!...
 *
 * eBloodBank Project is Plain Vanilla, Free and Open Source software.
 * developed as an Academic Project for Taiz University - CNDS department.
 *
 * @author Nashwan Doaqan<nashwan.doaqan@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.txt GPL
 * @copyright (c) 2015, Nashwan Doaqan
 * @version 1.0-alpha-6
 */

use EBloodBank\Controllers\FrontPage;

/*** App Bootstrap ************************************************************/

define('EBB_DIR', dirname(__FILE__));
//define('EBB_URL', '');

require EBB_DIR . '/app/bootstrap.php';

/*** FrontPage Controller *****************************************************/

$controller = new FrontPage();
$controller();
