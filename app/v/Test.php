<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Test_View extends Default_View {

	/**
	 * @return string
	 * @since 0.5.3
	 */
	public function get_title() {

		switch ( CURRENT_PAGE ) {

			case 'test':
			case 'view-test':
				return 'عرض فحص طبي';

			case 'add-test':
				return 'إضافة فحص طبي';

			case 'edit-test':
				return 'تحرير فحص طبي';

		}

	}

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<form id="form-test" class="form-block" method="POST">

				<div>
					<label for="test_type_id">نوع الفحص</label>
					<select id="test_type_id" name="test_type_id">
						<?php foreach( Test_Types::fetch_all() as $test_type ) : ?>
							<option value="<?php $test_type->display( 'tt_id', 'attr' ) ?>"><?php $test_type->display( 'tt_title' ) ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div>
					<label for="test_donor_id">المتبرع</label>
					<select id="test_donor_id" name="test_donor_id">
						<?php foreach( Donors::fetch_all() as $donor ) : ?>
							<option value="<?php $donor->display( 'donor_id', 'attr' ) ?>"><?php $donor->display( 'donor_name' ) ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div>
					<label for="test_date">التاريخ</label>
					<input type="date" name="test_date" id="test_date" />
				</div>

				<div>
					<label for="test_document">المستند</label>
					<input type="file" name="test_document" id="test_document" />
				</div>

				<div>
					<button type="submit">ارسال</button>
				</div>

				<input type="hidden" name="action" value="submit_test" />

			</form><?php

		$this->template_footer();

	}

}
