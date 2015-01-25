<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class City_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.2
	 */
	public function process_request() {

		if ( isset( $_POST['action'] ) && 'submit_city' === $_POST['action'] ) {

			$city_data = array();

			IF ( isset( $_POST['city_name'] ) ) {
				$city_data['city_name'] = filter_var( $_POST['city_name'], FILTER_SANITIZE_STRING );
			}

			$city_id = Cites::insert( $city_data );
			// TODO: Redirect
			die(0);

		}

	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function output_response() {

		$view = new City_View();
		$view();

	}

}