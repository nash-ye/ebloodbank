<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class Donor_View extends Default_View {

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

			<form id="form-donor" class="form-block" method="POST">

				<div>
					<label for="donor_name">الاسم</label>
					<input type="text" name="donor_name" id="donor_name" required="required" />
				</div>

				<div>
					<label for="donor_gender">الجنس</label>
					<select name="donor_gender" id="donor_gender">
						<option value="male">ذكر</option>
						<option value="female">أنثى</option>
					</select>
				</div>

				<div>
					<label for="donor_weight">الوزن</label>
					<input type="number" name="donor_weight" id="donor_weight" />
				</div>

				<div>
					<label for="donor_birthdate">تاريخ الميلاد</label>
					<input type="date" name="donor_birthdate" id="donor_birthdate" />
				</div>

				<div>
					<label for="donor_blood_group">فصيلة الدم</label>
					<select name="donor_blood_group" id="donor_blood_group">
						<option>-O</option>
						<option>+O</option>
						<option>-A</option>
						<option>+A</option>
						<option>-B</option>
						<option>+B</option>
						<option>-AB</option>
						<option>+AB</option>
					</select>
				</div>

				<div>
					<label for="donor_phone">رقم التلفون</label>
					<input type="phone" name="donor_phone" id="donor_phone" />
				</div>

				<div>
					<label for="donor_email">البريد الإلكتروني</label>
					<input type="email" name="donor_email" id="donor_email" />
				</div>

				<div>
					<label for="donor_distr_id">المدينة/المديرية</label>
					<select name="donor_distr_id" id="donor_distr_id">

						<?php

							$cites = Cites::fetch_all();

							if ( ! empty( $cites ) && is_array( $cites ) ) {

								foreach( $cites as $city ) { ?>

									<optgroup label="<?php $city->display( 'city_name', 'attr' ) ?>">

										<?php

											$districts = Districts::fetch_multi( 'SELECT * FROM district WHERE distr_city_id = ?', array( (int) $city->get( 'city_id' ) ) );

											if ( ! empty( $districts ) && is_array( $districts ) ) {

												foreach( $districts as $district ) { ?>
													<option value="<?php $district->display( 'distr_id', 'attr' ) ?>">
														<?php $district->display( 'distr_name' ) ?>
													</option><?php
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
					<button type="submit">ارسال</button>
				</div>

				<input type="hidden" name="action" value="submit_donor" />

			</form>

		</div> <!-- #content --><?php

		$this->template_footer();

	}

}
