<?php

namespace eBloodBank;

/**
 * @since 0.5.6
 */
class Dashboard_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.6
	 */
	public function process_request() {

		if ( isset( $_REQUEST['action'] ) ) {

			if ( 'signin' === $_REQUEST['action'] ) {

				if ( Sessions::is_signed_in() ) {
					die( -1 );
				}

				if ( isset( $_POST['user_logon'], $_POST['user_pass'] ) ) {

					$signed_in = Sessions::signin( $_POST['user_logon'], $_POST['user_pass'] );

					if ( $signed_in ) {
						redirect( 'index.php' );
					}

				}

			} elseif ( 'signout' === $_REQUEST['action'] ) {

				if ( Sessions::signout() ) {
					redirect( URL );
				}

			}

		}

	}

	/**
	 * @return void
	 * @since 0.5.6
	 */
	public function output_response() {

		$view = new Dashboard_View();
		$view();

	}

}