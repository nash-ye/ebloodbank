<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Test_Type_View extends Default_View {

	/**
	 * @return string
	 * @since 0.5.3
	 */
	public function get_title() {

		switch ( CURRENT_PAGE ) {

			case 'test-type':
			case 'view-test-type':
				return 'عرض نوع فحص';

			case 'add-test-type':
				return 'إضافة نوع فحص';

			case 'edit-test-type':
				return 'تحرير نوع فحص';

		}

	}

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<form id="form-test-type" class="form-block" method="POST">

				<div>
					<label for="tt_title">الاسم</label>
					<input type="text" name="tt_title" id="tt_title" required="required" />
				</div>

				<div>
					<label for="tt_priority">الأهمية</label>
					<input type="range" name="tt_priority" id="tt_priority" min="0" max="10" value="10" />
				</div>

				<div>
					<button type="submit">ارسال</button>
				</div>

				<input type="hidden" name="action" value="submit_test_type" />

			</form><?php

		$this->template_footer();

	}

}
