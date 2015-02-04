<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Bank_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function process_request() {

		if ( isset( $_POST['action'] ) && 'submit_bank' === $_POST['action'] ) {

			if ( current_user_can( 'add_bank' ) ) {

				$bank_data = array();

				IF ( isset( $_POST['bank_name'] ) ) {
					$bank_data['bank_name'] = filter_var( $_POST['bank_name'], FILTER_SANITIZE_STRING );
				}

				IF ( isset( $_POST['bank_phone'] ) ) {
					$bank_data['bank_phone'] = filter_var( $_POST['bank_phone'], FILTER_SANITIZE_STRING );
				}

				IF ( isset( $_POST['bank_email'] ) ) {
					$bank_data['bank_email'] = filter_var( $_POST['bank_email'], FILTER_SANITIZE_EMAIL );
				}

				IF ( isset( $_POST['bank_distr_id'] ) ) {
					$bank_data['bank_distr_id'] = filter_var( $_POST['bank_distr_id'], FILTER_SANITIZE_NUMBER_INT );
				}

				IF ( isset( $_POST['bank_address'] ) ) {
					$bank_data['bank_address'] = filter_var( $_POST['bank_address'], FILTER_SANITIZE_STRING );
				}

				if ( current_user_can( 'approve_test' ) ) {
					$bank_data['bank_status'] = 'approved';
				}

				$bank_id = Banks::insert( $bank_data );
				$submitted = is_vaild_ID( $bank_id );

				redirect( get_site_url( array(
					'page' => 'add-bank',
					'flag-submitted' => $submitted,
				) ) );

			}

		}

	}

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function output_response() {

		if ( current_user_can( 'add_bank' ) ) {
			$view = new Bank_View();
		} else {
			$view = new Error401_View();
		}

		$view();

	}

}