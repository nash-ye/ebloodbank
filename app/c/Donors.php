<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class Donors_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.2
	 */
	public function process_request() {

		if ( isset( $_GET['action'] ) && 'del_donor' === $_GET['action'] ) {

			if ( current_user_can( 'del_donor' ) ) {

				$donor_id = (int) $_GET['id'];
				$deleted = Donors::delete( $donor_id );

				redirect( get_site_url( array(
					'page' => 'donors',
					'flag-deleted' => $deleted,
				) ) );

			}

		}

	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function output_response() {

		$view = new Donors_View();

		if ( ! empty( $_GET['blood_group'] ) ) {
			$view->filter_args['blood_group'] = $_GET['blood_group'];
		}

		if ( ! empty( $_GET['distr_id'] ) ) {
			$view->filter_args['distr_id'] = (int) $_GET['distr_id'];
		}

		if ( ! empty( $_GET['city_id'] ) ) {
			$view->filter_args['city_id']  = (int) $_GET['city_id'];
		}

		$view();

	}

}