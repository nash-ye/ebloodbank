<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class District_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.2
	 */
	public function process_request() {

		if ( isset( $_POST['action'] ) && 'submit_distr' === $_POST['action'] ) {

			$distr_data = array();

			IF ( isset( $_POST['distr_name'] ) ) {
				$distr_data['distr_name'] = filter_var( $_POST['distr_name'], FILTER_SANITIZE_STRING );
			}

			IF ( isset( $_POST['distr_city_id'] ) ) {
				$distr_data['distr_city_id'] = (int) $_POST['distr_city_id'];
			}

			$distr_id = Districts::insert( $distr_data );
			// TODO: Redirect
			die(0);

		}

	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function output_response() {

		$view = new District_View();
		$view();

	}

}