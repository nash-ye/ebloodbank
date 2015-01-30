<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class User_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.2
	 */
	public function process_request() {

		if ( isset( $_POST['action'] ) && 'submit_user' === $_POST['action'] ) {

			if ( current_user_can( 'add_user' ) ) {

				$user_data = array();

				IF ( isset( $_POST['user_logon'] ) ) {
					$user_data['user_logon'] = filter_var( $_POST['user_logon'], FILTER_SANITIZE_STRING );
				}

				IF ( ! empty( $_POST['user_pass'] ) ) {
					$user_data['user_pass'] = password_hash( $_POST['user_pass'], PASSWORD_BCRYPT );
				}

				IF ( isset( $_POST['user_role'] ) ) {
					$user_data['user_role'] = filter_var( $_POST['user_role'], FILTER_SANITIZE_STRING );
				}

				$user_id = Users::insert( $user_data );
				$submitted = is_vaild_ID( $user_id );

				redirect( get_site_url( array(
					'page' => 'add-user',
					'flag-submitted' => $submitted,
				) ) );

			}

		}

	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function output_response() {

		$view = new User_View();
		$view();

	}

}