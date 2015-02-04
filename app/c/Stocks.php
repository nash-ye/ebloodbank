<?php

namespace eBloodBank;

/**
 * @since 0.5.4
 */
class Stocks_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.4
	 */
	public function process_request() {

		if ( isset( $_GET['action'] ) && 'delete_stock' === $_GET['action'] ) {

			if ( current_user_can( 'delete_stock' ) ) {

				$stock_id = (int) $_GET['id'];
				$deleted = Stocks::delete( $stock_id );

				redirect( get_site_url( array(
					'page' => 'stocks',
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

		$view = new Stocks_View();

		if ( isset( $_GET['bank_id'] ) ) {
			$view->filter_args['bank_id'] = (int) $_GET['bank_id'];
		}

		$view();

	}

}