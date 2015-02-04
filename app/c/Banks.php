<?php

namespace eBloodBank;

/**
 * @since 0.5.4
 */
class Banks_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.4
	 */
	public function process_request() {

		if ( isset( $_GET['action'] ) && 'delete_bank' === $_GET['action'] ) {

			if ( current_user_can( 'delete_bank' ) ) {

				$bank_id = (int) $_GET['id'];
				$deleted = Banks::delete( $bank_id );

				redirect( get_site_url( array(
					'page' => 'banks',
					'flag-deleted' => $deleted,
				) ) );

			}

		}

	}

	/**
	 * @return void
	 * @since 0.5.4
	 */
	public function output_response() {

		$view = new Banks_View();
		$view();

	}

}