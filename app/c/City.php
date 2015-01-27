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
			$submitted = is_vaild_ID( $city_id );

			redirect( "?page=add-city&flag-submitted={$submitted}" );

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