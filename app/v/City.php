<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class City_View extends Default_View {

	/**
	 * @return string
	 * @since 0.2
	 */
	public function get_title() {
		return 'eBloodBank';
	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function __invoke() {

		$this->template_header(); ?>

		<div id="content">

			<form id="form-city" class="form-block" method="POST">

				<div>
					<label for="city_name">الاسم</label>
					<input type="text" name="city_name" id="city_name" required="required" />
				</div>

				<div>
					<button type="submit">ارسال</button>
				</div>

				<input type="hidden" name="action" value="submit_city" />

			</form>

		</div> <!-- #content --><?php

		$this->template_footer();

	}

}
