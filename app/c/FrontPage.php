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

		if ( ! empty( $_GET['page'] ) ) {
			define( 'eBloodBank\CURRENT_PAGE', $_GET['page'] );
		} else {
			define( 'eBloodBank\CURRENT_PAGE', 'frontpage' );
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

			case 'user':
			case 'add-user':
				new User_Controller();
				break;

			case 'users':
				new Users_Controller();
				break;

			case 'donor':
			case 'add-donor':
				new Donor_Controller();
				break;

			case 'donors':
				new Donors_Controller();
				break;

			default:
				$view = new FrontPage_View();
				$view();
				break;

		}

	}

}
