<?php

namespace eBloodBank;

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
 * @version 0.5.8-alpha
 */

/*** Core Bootstrap ***********************************************************/

require __DIR__ . '/config.php';
require __DIR__ . '/loader.php';

/*** FrontPage Controller *****************************************************/

new FrontPage_Controller();