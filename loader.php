<?php

namespace eBloodBank;

/*** Constants ****************************************************************/

define( 'eBloodBank\VERSION', '0.1-alpha' );

if ( ! defined( 'eBloodBank\DEBUG' ) ) {
	define( 'eBloodBank\DEBUG', FALSE );
}

/*** Error Reporting **********************************************************/

if ( DEBUG ) {

	error_reporting( E_ALL );

} else {

	error_reporting(
			E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR |
			E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING |
			E_RECOVERABLE_ERROR
		);

}

/*** Default TimeZone *********************************************************/

date_default_timezone_set( 'UTC' );

/*** Database Connection ******************************************************/


try {

	$db = new \PDO( sprintf( 'mysql:host=%s;dbname=%s', DB_HOST, DB_NAME ), DB_USER, DB_PASS );
	$db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

} catch ( \PDOException $ex ) {
	die( 'Database Error!' );
}

/*** Load Includes ************************************************************/

require 'includes/helpers.php';

/*** App Classes **************************************************************/

/**
 * @return void
 * @since 0.1
 */
function class_loader( $class_name ) {

	switch( $class_name ) {

		/*** Models ************************************************************/

		case 'eBloodBank\Model':
			require 'app/m/model.php';
			break;

		case 'eBloodBank\City':
			require 'app/m/city.php';
			break;

		case 'eBloodBank\Cites':
			require 'app/m/cites.php';
			break;

		case 'eBloodBank\Dstrict':
			require 'app/m/dstrict.php';
			break;

		case 'eBloodBank\Dstricts':
			require 'app/m/dstricts.php';
			break;

		case 'eBloodBank\Donor':
			require 'app/m/donor.php';
			break;

		case 'eBloodBank\Donors':
			require 'app/m/donors.php';
			break;

		case 'eBloodBank\User':
			require 'app/m/user.php';
			break;

		case 'eBloodBank\Users':
			require 'app/m/users.php';
			break;

		/*** Views ************************************************************/

		case 'eBloodBank\View':
			require 'app/v/View.php';
			break;

		case 'eBloodBank\Default_View':
			require 'app/v/View.php';
			break;

		case 'eBloodBank\FrontPage_View':
			require 'app/v/FrontPage.php';
			break;

		/*** FrontPages *******************************************************/

		case 'eBloodBank\Controller':
			require 'app/c/Controller.php';
			break;

		case 'eBloodBank\FrontPage_Controller':
			require 'app/c/FrontPage.php';
			break;

	}

}

\spl_autoload_register( 'eBloodBank\class_loader' );