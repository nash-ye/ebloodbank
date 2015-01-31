<?php

namespace eBloodBank;

/**
 * @since 0.4.7
 */
class Error404_View extends Default_View {

	/**
	 * @return void
	 * @since 0.4.7
	 */
	public function get_title() {
		return 'خطأ: غير موجود';
	}

	/**
	 * @return void
	 * @since 0.4.7
	 */
	public function __invoke() {

		header( 'HTTP/1.0 404 Not Found' );

		$this->template_header(); ?>

			<div class="error-msg error-404-msg">
				<p>المعذرة، هذه الصفحة غير موجودة.</p>
			</div><?php

		$this->template_footer();

	}

}
