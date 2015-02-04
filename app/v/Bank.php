<?php

namespace eBloodBank;

/**
 * @since 0.5.4
 */
class Bank_View extends Default_View {

	/**
	 * @return string
	 * @since 0.5.4
	 */
	public function get_title() {

		switch ( CURRENT_PAGE ) {

			case 'bank':
			case 'view-bank':
				return 'عرض بنك دم';

			case 'add-bank':
				return 'إضافة بنك دم';

			case 'edit-bank':
				return 'تحرير بنك دم';

		}

	}

	/**
	 * @return void
	 * @since 0.5.4
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<form id="form-bank" class="form-block" method="POST">

				<div>
					<label for="bank_name">الاسم</label>
					<input type="text" name="bank_name" id="bank_name" required="required" />
				</div>

				<div>
					<label for="bank_phone">رقم التلفون</label>
					<input type="phone" name="bank_phone" id="bank_phone" required="required" />
				</div>

				<div>
					<label for="bank_email">البريد الإلكتروني</label>
					<input type="email" name="bank_email" id="bank_email" />
				</div>

				<div>
					<label for="bank_distr_id">المدينة/المديرية</label>
					<select name="bank_distr_id" id="bank_distr_id">

						<?php

							$cites = Cites::fetch_all();

							if ( ! empty( $cites ) && is_array( $cites ) ) {

								foreach( $cites as $city ) { ?>

									<optgroup label="<?php $city->display( 'city_name', 'attr' ) ?>">

										<?php

											$districts = Districts::fetch_multi( 'SELECT * FROM district WHERE distr_city_id = ?', array( (int) $city->get( 'city_id' ) ) );

											if ( ! empty( $districts ) && is_array( $districts ) ) {

												foreach( $districts as $district ) { ?>
													<option value="<?php $district->display( 'distr_id', 'attr' ) ?>"><?php $district->display( 'distr_name' ) ?></option><?php
												}

											}

										?>

									</optgroup><?php

								}

							}

						?>

					</select>
				</div>

				<div>
					<label for="bank_address">العنوان</label>
					<input type="text" name="bank_address" id="bank_address" required="required" />
				</div>

				<div>
					<button type="submit">ارسال</button>
				</div>

				<input type="hidden" name="action" value="submit_bank" />

			</form><?php

		$this->template_footer();

	}

}
