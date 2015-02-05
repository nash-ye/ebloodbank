<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class City_View extends Default_View {

	/**
	 * @return void
	 * @since 0.2
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<form id="form-city" class="form-block" method="POST">

				<div>
					<label for="city_name">الاسم</label>
					<input type="text" name="city_name" id="city_name" required="required" />
				</div>

				<div>
					<button type="submit">ارسال</button>
				</div>

				<input type="hidden" name="action" value="submit_city" />

			</form><?php

		$this->template_footer();

	}

	/**
	 * @return string
	 * @since 0.5.6
	 */
	public function get_fa_icon() {

		switch ( CURRENT_PAGE ) {

			case 'add-city':
				return '<i class="fa fa-plus"></i>';

		}

	}

	/**
	 * @return string
	 * @since 0.2
	 */
	public function get_title() {

		switch ( CURRENT_PAGE ) {

			case 'city':
			case 'view-city':
				return 'عرض مدينة';

			case 'add-city':
				return 'إضافة مدينة';

			case 'edit-city':
				return 'تحرير مدينة';

		}

	}

}
