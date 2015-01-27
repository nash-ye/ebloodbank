<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class Donor_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.2
	 */
	public function process_request() {

		if ( isset( $_POST['action'] ) && 'submit_donor' === $_POST['action'] ) {

			$donor_data = array();

			IF ( isset( $_POST['donor_name'] ) ) {
				$donor_data['donor_name'] = filter_var( $_POST['donor_name'], FILTER_SANITIZE_STRING );
			}

			IF ( isset( $_POST['donor_gender'] ) ) {

				if ( in_array( $_POST['donor_gender'], array( 'male', 'female', 'unknown' ) ) ) {
					$donor_data['donor_gender'] = $_POST['donor_gender'];
				}

			}

			IF ( isset( $_POST['donor_weight'] ) ) {
				$donor_data['donor_weight'] = filter_var( $_POST['donor_weight'], FILTER_SANITIZE_NUMBER_FLOAT );
			}

			IF ( isset( $_POST['donor_birthdate'] ) ) {
				$donor_data['donor_birthdate'] = filter_var( $_POST['donor_birthdate'], FILTER_SANITIZE_STRING );
			}

			IF ( isset( $_POST['donor_blood_group'] ) ) {

				if ( in_array( $_POST['donor_blood_type'], array( '-O', '+O', '-A', '+A', '-B', '+B', '-AB', '+AB' ) ) ) {
					$donor_data['donor_blood_group'] = $_POST['donor_blood_group'];
				}

			}

			IF ( isset( $_POST['donor_phone'] ) ) {
				$donor_data['donor_phone'] = filter_var( $_POST['donor_phone'], FILTER_SANITIZE_STRING );
			}

			IF ( isset( $_POST['donor_email'] ) ) {
				$donor_data['donor_email'] = filter_var( $_POST['donor_email'], FILTER_SANITIZE_EMAIL );
			}

			IF ( isset( $_POST['donor_distr_id'] ) ) {
				$donor_data['donor_distr_id'] = (int) $_POST['donor_distr_id'];
			}

			$donor_id = Donors::insert( $donor_data );
			$submitted = is_vaild_ID( $donor_id );

			redirect( "?page=add-donor&flag-submitted={$submitted}" );

		}

	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function output_response() {

		$view = new Donor_View();
		$view();

	}

}