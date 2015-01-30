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

			if ( current_user_can( 'add_distr' ) ) {

				$distr_data = array();

				IF ( isset( $_POST['distr_name'] ) ) {
					$distr_data['distr_name'] = filter_var( $_POST['distr_name'], FILTER_SANITIZE_STRING );
				}

				IF ( isset( $_POST['distr_city_id'] ) ) {
					$distr_data['distr_city_id'] = (int) $_POST['distr_city_id'];
				}

				$distr_id = Districts::insert( $distr_data );
				$submitted = is_vaild_ID( $distr_id );

				redirect( get_site_url( array(
					'page' => 'add-distr',
					'flag-submitted' => $submitted,
				) ) );

			}

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