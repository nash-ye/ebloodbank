<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class FrontPage_Controller extends Controller {

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $page;

	/**
	 * @return void
	 * @since 0.1
	 */
	public function process_request() {

		if ( ! empty( $_GET['page'] ) ) {
			$this->page = $_GET['page'];
		} else {
			$this->page = 'frontpage';
		}

	}

	/**
	 * @return void
	 * @since 0.1
	 */
	public function output_response() {

		switch( $this->page ) {

			default:
				$view = new FrontPage_View();
				$view();
				break;

		}

	}

}
