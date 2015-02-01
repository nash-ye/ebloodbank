<?php

namespace eBloodBank;

/**
 * @since 0.5.2
 */
class About_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.5.2
	 */
	public function process_request() {
	}

	/**
	 * @return void
	 * @since 0.5.2
	 */
	public function output_response() {

		$view = new About_View();
		$view();

	}

}