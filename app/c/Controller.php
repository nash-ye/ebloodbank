<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
abstract class Controller {

	/**
	 * @since 0.1
	 */
	public function __construct() {
		$this->process_request();
		$this->output_response();
	}

	/**
	 * @return void
	 * @since 0.1
	 */
	abstract public function process_request();

	/**
	 * @return void
	 * @since 0.1
	 */
	abstract public function output_response();

}