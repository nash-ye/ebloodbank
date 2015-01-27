<?php

namespace eBloodBank;

/**
 * @since 0.3
 */
class Districts_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.3
	 */
	public function process_request() {
	}

	/**
	 * @return void
	 * @since 0.3
	 */
	public function output_response() {

		$view = new Districts_View();
		$view();

	}

}