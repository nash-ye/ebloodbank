<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class FrontPage_View extends Default_View {

	/**
	 * @return string
	 * @since 0.1
	 */
	public function get_title() {
		return 'eBloodBank';
	}

	/**
	 * @return void
	 * @since 0.1
	 */
	public function __invoke() {

		$this->template_header(); ?>

		<div id="content">

		</div> <!-- #content --><?php

		$this->template_footer();

	}

}
