<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class Donors_Controller extends Controller {

	/**
	 * @return void
	 * @since 0.2
	 */
	public function process_request() {
	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function output_response() {

		$view = new Donors_View();
		$view();

	}

}