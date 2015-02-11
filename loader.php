<?php

namespace eBloodBank;

/*** Constants ****************************************************************/

if ( ! defined( 'DB_NAME' ) ) {
	define( 'DB_NAME', 'ebloodbank' );
	define( 'eBloodBank\CREATE_DB', TRUE );
}

define( 'eBloodBank\VERSION', '0.6-alpha' );
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

	$db = new \PDO( sprintf( 'mysql:host=%s;charset=utf8;', DB_HOST ), DB_USER, DB_PASS );
	$db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
	$db->exec( sprintf( 'USE `%s`', DB_NAME ) );

} catch ( \PDOException $ex ) {
	die( 'Database Error, Please make sure you had setup the database.' );
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

		case 'eBloodBank\Models':
			require 'app/m/Models.php';
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

		case 'eBloodBank\Bank':
			require 'app/m/Bank.php';
			break;

		case 'eBloodBank\Banks':
			require 'app/m/Banks.php';
			break;

		case 'eBloodBank\Stock':
			require 'app/m/Stock.php';
			break;

		case 'eBloodBank\Stocks':
			require 'app/m/Stocks.php';
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

		case 'eBloodBank\Donor_View':
			require 'app/v/Donor.php';
			break;

		case 'eBloodBank\Donors_View':
			require 'app/v/Donors.php';
			break;

		case 'eBloodBank\User_View':
			require 'app/v/User.php';
			break;

		case 'eBloodBank\Users_View':
			require 'app/v/Users.php';
			break;

		case 'eBloodBank\Bank_View':
			require 'app/v/Bank.php';
			break;

		case 'eBloodBank\Banks_View':
			require 'app/v/Banks.php';
			break;

		case 'eBloodBank\Stock_View':
			require 'app/v/Stock.php';
			break;

		case 'eBloodBank\Stocks_View':
			require 'app/v/Stocks.php';
			break;

		case 'eBloodBank\Default_View':
			require 'app/v/View.php';
			break;

		case 'eBloodBank\FrontPage_View':
			require 'app/v/FrontPage.php';
			break;

		case 'eBloodBank\Error401_View':
			require 'app/v/Error401.php';
			break;

		case 'eBloodBank\Error404_View':
			require 'app/v/Error404.php';
			break;

		case 'eBloodBank\Dashboard_View':
			require 'app/v/Dashboard.php';
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

		case 'eBloodBank\Donor_Controller':
			require 'app/c/Donor.php';
			break;

		case 'eBloodBank\Donors_Controller':
			require 'app/c/Donors.php';
			break;

		case 'eBloodBank\User_Controller':
			require 'app/c/User.php';
			break;

		case 'eBloodBank\Users_Controller':
			require 'app/c/Users.php';
			break;

		case 'eBloodBank\Bank_Controller':
			require 'app/c/Bank.php';
			break;

		case 'eBloodBank\Banks_Controller':
			require 'app/c/Banks.php';
			break;

		case 'eBloodBank\Stock_Controller':
			require 'app/c/Stock.php';
			break;

		case 'eBloodBank\Stocks_Controller':
			require 'app/c/Stocks.php';
			break;

		case 'eBloodBank\FrontPage_Controller':
			require 'app/c/FrontPage.php';
			break;

		case 'eBloodBank\Dashboard_Controller':
			require 'app/c/Dashboard.php';
			break;

		case 'eBloodBank\About_Controller':
			require 'app/c/About.php';
			break;

	}

}

\spl_autoload_register( 'eBloodBank\class_loader' );