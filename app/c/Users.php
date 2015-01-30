<?php

namespace eBloodBank;

/**
 * @since 0.3
 */
class Users_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.3
	 */
	public function process_request() {

		if ( isset( $_GET['action'] ) && 'delete_user' === $_GET['action'] ) {

			if ( current_user_can( 'delete_user' ) ) {

				$user_id = (int) $_GET['id'];
				$deleted = Users::delete( $user_id );

				redirect( get_site_url( array(
					'page' => 'users',
					'flag-deleted' => $deleted,
				) ) );

			}

		}

	}

	/**
	 * @return void
	 * @since 0.3
	 */
	public function output_response() {

		$view = new Users_View();
		$view();

	}

}