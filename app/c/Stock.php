<?php

namespace eBloodBank;

/**
 * @since 0.5.4
 */
class Stock_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.4
	 */
	public function process_request() {

		if ( isset( $_POST['action'] ) && 'submit_stock' === $_POST['action'] ) {

			if ( current_user_can( 'add_stock' ) ) {

				$stock_data = array();

				IF ( isset( $_POST['stock_bank_id'] ) ) {
					$stock_data['stock_bank_id'] = filter_var( $_POST['stock_bank_id'], FILTER_SANITIZE_NUMBER_INT );
				}

				IF ( isset( $_POST['stock_blood_group'] ) ) {
					if ( in_array( $_POST['stock_blood_group'], Stock::$blood_groups ) ) {
						$stock_data['stock_blood_group'] = $_POST['stock_blood_group'];
					}
				}

				IF ( isset( $_POST['stock_quantity'] ) ) {
					$stock_data['stock_quantity'] = filter_var( $_POST['stock_quantity'], FILTER_SANITIZE_NUMBER_INT );
				}

				$stock_id = Stocks::insert( $stock_data );
				$submitted = is_vaild_ID( $stock_id );

				redirect( get_site_url( array(
					'page' => 'add-stock',
					'flag-submitted' => $submitted,
				) ) );

			}

		}

	}

	/**
	 * @return void
	 * @since 0.5.4
	 */
	public function output_response() {

		if ( current_user_can( 'add_stock' ) ) {
			$view = new Stock_View();
		} else {
			$view = new Error401_View();
		}

		$view();

	}

}