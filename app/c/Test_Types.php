<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Test_Types_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function process_request() {

		if ( isset( $_GET['action'] ) && 'delete_test_type' === $_GET['action'] ) {

			if ( current_user_can( 'delete_test_type' ) ) {

				$tt_id = (int) $_GET['id'];
				$deleted = Test_Types::delete( $tt_id );

				redirect( get_site_url( array(
					'page' => 'test-types',
					'flag-deleted' => $deleted,
				) ) );

			}

		}

	}

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function output_response() {

		$view = new Test_Types_View();
		$view();

	}

}