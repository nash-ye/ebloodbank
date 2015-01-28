<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class Donors_View extends Default_View {

	/**
	 * @var array
	 * @since 0.4
	 */
	public $filter_args = arraY();

	/**
	 * @return string
	 * @since 0.2
	 */
	public function get_title() {
		return 'المتبرعين';
	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<a href="?page=add-donor">
				<button type="button">أضف متبرع جديد</button>
			</a>

			<table id="table-donors" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
					<th>الجنس</th>
					<th>العمر</th>
					<th>فصيلة الدم</th>
					<th>المدينة/المديرية</th>
					<th>رقم التلفون</th>
				</thead>

				<tbody>

					<?php

						$donors = Donors::fetch_by_args( $this->filter_args );

						if ( ! empty( $donors ) && is_array( $donors ) ) {

							foreach( $donors as $donor ) {

								?>

								<tr>
									<td><?php $donor->display( 'donor_id' ) ?></td>
									<td><?php $donor->display( 'donor_name' ) ?></td>
									<td><?php $donor->display( 'donor_gender' ) ?></td>
									<td><?php $donor->display( 'donor_birthdate' ) ?></td>
									<td><?php $donor->display( 'donor_blood_group' ) ?></td>
									<td><?php $donor->display( 'donor_distr_id' ) ?></td>
									<td><?php $donor->display( 'donor_phone' ) ?></td>
								</tr>

								<?php

							}

						}

					?>

				</tbody>

			</table><?php

		$this->template_footer();

	}

}
