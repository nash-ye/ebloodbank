<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class District_View extends Default_View {

	/**
	 * @return string
	 * @since 0.2
	 */
	public function get_title() {

		switch ( CURRENT_PAGE ) {

			case 'distr':
			case 'view-distr':
				return 'عرض مديرية';

			case 'add-distr':
				return 'إضافة مديرية';

			case 'edit-distr':
				return 'تحرير مديرية';

		}

	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<form id="form_distr" method="POST">

				<div>
					<label for="distr_name">الاسم</label>
					<input type="text" name="distr_name" id="distr_name" required="required" />
				</div>

				<div>
					<label for="distr_city_id">الاسم</label>
					<select id="distr_city_id" name="distr_city_id">
						<?php

							$cites = Cites::fetch_all();

							if ( ! empty( $cites ) && is_array( $cites ) ) {

								foreach( $cites as $city ) { ?>
									<option value="<?php $city->display( 'city_id', 'attr' ) ?>">
										<?php $city->display( 'city_name' ) ?>
									</option><?php
								}

							}

						?>
					</select>
				</div>

				<div>
					<button type="submit">ارسال</button>
				</div>

				<input type="hidden" name="action" value="submit_distr" />

			</form><?php

		$this->template_footer();

	}

}
