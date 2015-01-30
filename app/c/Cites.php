<?php

namespace eBloodBank;

/**
 * @since 0.3
 */
class Cites_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.3
	 */
	public function process_request() {

		if ( isset( $_GET['action'] ) && 'delete_city' === $_GET['action'] ) {

			if ( current_user_can( 'delete_city' ) ) {

				$city_id = (int) $_GET['id'];
				$deleted = Cites::delete( $city_id );

				redirect( get_site_url( array(
					'page' => 'cites',
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

		$view = new Cites_View();
		$view();

	}

}