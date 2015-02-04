<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Tests_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function process_request() {

		if ( isset( $_GET['action'] ) && 'delete_test' === $_GET['action'] ) {

			if ( current_user_can( 'delete_test' ) ) {

				$test_id = (int) $_GET['id'];
				$deleted = Tests::delete( $test_id );

				redirect( get_site_url( array(
					'page' => 'tests',
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

		$view = new Tests_View();
		$view();

	}

}