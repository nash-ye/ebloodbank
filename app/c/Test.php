<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Test_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function process_request() {

		if ( isset( $_POST['action'] ) && 'submit_test' === $_POST['action'] ) {

			if ( current_user_can( 'add_test' ) ) {

				$test_data = array();

				IF ( isset( $_POST['test_date'] ) ) {
					$test_data['test_date'] = filter_var( $_POST['test_date'], FILTER_SANITIZE_STRING );
				}

				IF ( isset( $_POST['test_type_id'] ) ) {
					$test_data['test_type_id'] = filter_var( $_POST['test_type_id'], FILTER_SANITIZE_NUMBER_INT );
				}

				IF ( isset( $_POST['test_donor_id'] ) ) {
					$test_data['test_donor_id'] = filter_var( $_POST['test_donor_id'], FILTER_SANITIZE_NUMBER_INT );
				}

				IF ( isset( $_POST['test_document'] ) ) {
					//$test_data['test_document'] = $_FILES['test_document'];
				}

				if ( current_user_can( 'approve_test' ) ) {
					$test_data['test_status'] = 'approved';
				}

				$test_id = Tests::insert( $test_data );
				$submitted = is_vaild_ID( $test_id );

				redirect( get_site_url( array(
					'page' => 'add-test',
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

		if ( current_user_can( 'add_test' ) ) {
			$view = new Test_View();
		} else {
			$view = new Error401_View();
		}

		$view();

	}

}