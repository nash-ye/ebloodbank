<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class FrontPage_Controller extends Controller {

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $page;

	/**
	 * @return void
	 * @since 0.1
	 */
	public function process_request() {

		if ( ! defined( 'eBloodBank\CURRENT_PAGE' ) ) {

			if ( ! empty( $_GET['page'] ) ) {
				define( 'eBloodBank\CURRENT_PAGE', $_GET['page'] );
			} else {
				define( 'eBloodBank\CURRENT_PAGE', 'frontpage' );
			}

		}

		if ( isset( $_GET['action'] ) && 'signout' === $_GET['action'] ) {

			if ( Sessions::signout() ) {
				redirect( get_site_url() );
			}

		}

	}

	/**
	 * @return void
	 * @since 0.1
	 */
	public function output_response() {

		switch( CURRENT_PAGE ) {

			case 'city':
			case 'add-city':
				new City_Controller();
				break;

			case 'cites':
				new Cites_Controller();
				break;

			case 'distr':
			case 'add-distr':
				new District_Controller();
				break;

			case 'distrs':
				new Districts_Controller();
				break;

			case 'donor':
			case 'add-donor':
				new Donor_Controller();
				break;

			case 'donors':
				new Donors_Controller();
				break;

			case 'user':
			case 'add-user':
				new User_Controller();
				break;

			case 'users':
				new Users_Controller();
				break;

			case 'bank':
			case 'add-bank':
				new Bank_Controller();
				break;

			case 'banks':
				new Banks_Controller();
				break;

			case 'stock':
			case 'add-stock':
				new Stock_Controller();
				break;

			case 'stocks':
				new Stocks_Controller();
				break;

			case 'about':
				new About_Controller();
				break;

			case 'dashboard':
				new Dashboard_Controller();
				break;

			case 'frontpage':
				$view = new FrontPage_View();
				$view();
				break;

			default:
				$view = new Error404_View();
				$view();
				break;

		}

	}

}
