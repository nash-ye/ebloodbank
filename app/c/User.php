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

			$user_data = array();

			IF ( isset( $_POST['user_logon'] ) ) {
				$user_data['user_logon'] = filter_var( $_POST['user_logon'], FILTER_SANITIZE_STRING );
			}

			IF ( ! empty( $_POST['user_pass'] ) ) {
				$user_data['user_pass'] = $_POST['user_pass'];
			}

			IF ( isset( $_POST['user_role'] ) ) {
				$user_data['user_role'] = filter_var( $_POST['user_role'], FILTER_SANITIZE_STRING );
			}

			IF ( isset( $_POST['user_status'] ) ) {
				$user_data['user_status'] = filter_var( $_POST['user_status'], FILTER_SANITIZE_STRING );
			}

			$user_id = Users::insert( $user_data );
			$submitted = is_vaild_ID( $user_id );

			redirect( "?page=add-user&flag-submitted={$submitted}" );

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