<?php

namespace eBloodBank;

/**
 * @since 0.3
 */
class Districts_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.3
	 */
	public function process_request() {

		if ( isset( $_GET['action'] ) && 'del_distr' === $_GET['action'] ) {

			if ( current_user_can( 'del_distr' ) ) {

				$distr_id = (int) $_GET['id'];
				$deleted = Districts::delete( $distr_id );

				redirect( get_site_url( array(
					'page' => 'distrs',
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

		$view = new Districts_View();
		$view();

	}

}