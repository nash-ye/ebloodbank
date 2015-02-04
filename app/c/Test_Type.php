<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Test_Type_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function process_request() {

		if ( isset( $_POST['action'] ) && 'submit_test_type' === $_POST['action'] ) {

			if ( current_user_can( 'add_test_type' ) ) {

				$test_type_data = array();

				IF ( isset( $_POST['tt_title'] ) ) {
					$test_type_data['tt_title'] = filter_var( $_POST['tt_title'], FILTER_SANITIZE_STRING );
				}

				IF ( isset( $_POST['tt_priority'] ) ) {
					$test_type_data['tt_priority'] = filter_var( $_POST['tt_priority'], FILTER_SANITIZE_NUMBER_INT );
				}

				$tt_id = Test_Types::insert( $test_type_data );
				$submitted = is_vaild_ID( $tt_id );

				redirect( get_site_url( array(
					'page' => 'add-test-type',
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

		if ( current_user_can( 'add_test_type' ) ) {
			$view = new Test_Type_View();
		} else {
			$view = new Error401_View();
		}

		$view();

	}

}