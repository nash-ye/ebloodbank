<?php

namespace eBloodBank;

/**
 * @since 0.4.7
 */
class Error401_View extends Default_View {

	/**
	 * @return void
	 * @since 0.4.7
	 */
	public function get_title() {
		return 'خطأ: غير مصرح';
	}

	/**
	 * @return void
	 * @since 0.4.7
	 */
	public function __invoke() {

		header( 'HTTP/1.1 401 Unauthorized' );

		$this->template_header(); ?>

			<div class="error-msg error-401-msg">
				<p>المعذرة، غير مسموح لك دخول هذه الصفحة.</p>
			</div><?php

		$this->template_footer();

	}

}
