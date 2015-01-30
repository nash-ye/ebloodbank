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

		if ( isset( $_GET['action'] ) ) {

			$donor_id = (int) $_GET['id'];

			if ( empty( $donor_id ) ) {
				die( -1 );
			}

			if ( 'delete_donor' === $_GET['action'] ) {

				if ( current_user_can( 'delete_donor' ) ) {

					$deleted = Donors::delete( $donor_id );

					redirect( get_site_url( array(
						'page' => 'donors',
						'flag-deleted' => $deleted,
					) ) );

				}

			} elseif ( 'approve_donor' === $_GET['action'] ) {

				if ( current_user_can( 'approve_donor' ) ) {

					$donor = Donors::fetch_by_ID( $donor_id );

					if ( ! empty( $donor ) && $donor->is_pending() ) {

						$approved = Donors::update( $donor_id, array(
							'donor_status' => 'approved',
						) );

						redirect( get_site_url( array(
							'page' => 'donors',
							'flag-approved' => $approved,
						) ) );

					}

				}

			}

		}

	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function output_response() {

		$view = new Donors_View();

		if ( current_user_can( 'approve_donor' ) ) {
			$view->filter_args['status']  = 'all';
		} else {
			$view->filter_args['status']  = 'approved';
		}

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