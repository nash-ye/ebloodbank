<?php

namespace eBloodBank;

/*** Constants ****************************************************************/

define( 'eBloodBank\VERSION', '0.5.1-alpha' );
define( 'eBloodBank\DIR', dirname( __FILE__ ) );

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

	$db = new \PDO( sprintf( 'mysql:host=%s;dbname=%s;charset=utf8', DB_HOST, DB_NAME ), DB_USER, DB_PASS );
	$db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

} catch ( \PDOException $ex ) {
	die( 'Database Error!' );
}

/*** Load Includes ************************************************************/

require 'includes/helpers.php';
require 'includes/password.php';
require 'includes/sessions.php';
require 'includes/capabilities.php';

/*** Sessions *****************************************************************/

if ( session_status() !== PHP_SESSION_ACTIVE ) {
	session_start();
}

/*** App Classes **************************************************************/

/**
 * @return void
 * @since 0.1
 */
function class_loader( $class_name ) {

	switch( $class_name ) {

		/*** Models ************************************************************/

		case 'eBloodBank\Model':
		case 'eBloodBank\Model_Meta':
			require 'app/m/Model.php';
			break;

		case 'eBloodBank\City':
			require 'app/m/City.php';
			break;

		case 'eBloodBank\Cites':
			require 'app/m/Cites.php';
			break;

		case 'eBloodBank\District':
			require 'app/m/District.php';
			break;

		case 'eBloodBank\Districts':
			require 'app/m/Districts.php';
			break;

		case 'eBloodBank\Donor':
			require 'app/m/Donor.php';
			break;

		case 'eBloodBank\Donors':
			require 'app/m/Donors.php';
			break;

		case 'eBloodBank\User':
			require 'app/m/User.php';
			break;

		case 'eBloodBank\Users':
			require 'app/m/Users.php';
			break;

		/*** Views ************************************************************/

		case 'eBloodBank\View':
			require 'app/v/View.php';
			break;

		case 'eBloodBank\City_View':
			require 'app/v/City.php';
			break;

		case 'eBloodBank\Cites_View':
			require 'app/v/Cites.php';
			break;

		case 'eBloodBank\District_View':
			require 'app/v/District.php';
			break;

		case 'eBloodBank\Districts_View':
			require 'app/v/Districts.php';
			break;

		case 'eBloodBank\User_View':
			require 'app/v/User.php';
			break;

		case 'eBloodBank\Users_View':
			require 'app/v/Users.php';
			break;

		case 'eBloodBank\Donor_View':
			require 'app/v/Donor.php';
			break;

		case 'eBloodBank\Donors_View':
			require 'app/v/Donors.php';
			break;

		case 'eBloodBank\Default_View':
			require 'app/v/View.php';
			break;

		case 'eBloodBank\FrontPage_View':
			require 'app/v/FrontPage.php';
			break;

		case 'eBloodBank\SignIn_View':
			require 'app/v/SignIn.php';
			break;

		case 'eBloodBank\Error401_View':
			require 'app/v/Error401.php';
			break;

		case 'eBloodBank\Error404_View':
			require 'app/v/Error404.php';
			break;

		case 'eBloodBank\About_View':
			require 'app/v/About.php';
			break;

		/*** Controllers ******************************************************/

		case 'eBloodBank\Controller':
			require 'app/c/Controller.php';
			break;

		case 'eBloodBank\City_Controller':
			require 'app/c/City.php';
			break;

		case 'eBloodBank\Cites_Controller':
			require 'app/c/Cites.php';
			break;

		case 'eBloodBank\District_Controller':
			require 'app/c/District.php';
			break;

		case 'eBloodBank\Districts_Controller':
			require 'app/c/Districts.php';
			break;

		case 'eBloodBank\User_Controller':
			require 'app/c/User.php';
			break;

		case 'eBloodBank\Users_Controller':
			require 'app/c/Users.php';
			break;

		case 'eBloodBank\Donor_Controller':
			require 'app/c/Donor.php';
			break;

		case 'eBloodBank\Donors_Controller':
			require 'app/c/Donors.php';
			break;

		case 'eBloodBank\FrontPage_Controller':
			require 'app/c/FrontPage.php';
			break;

		case 'eBloodBank\SignIn_Controller':
			require 'app/c/SignIn.php';
			break;

		case 'eBloodBank\About_Controller':
			require 'app/c/About.php';
			break;

	}

}

\spl_autoload_register( 'eBloodBank\class_loader' );