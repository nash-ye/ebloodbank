<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class Donors_View extends Default_View {

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

		<div id="content">

			<table id="table-donors" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
					<th>الجنس</th>
					<th>الوزن</th>
					<th>العمر</th>
					<th>فصيلة الدم</th>
					<th>المدينة/المديرية</th>
					<th>رقم التلفون</th>
					<th>البريد الإلكتروني</th>
				</thead>

				<tbody>

					<?php

						$donors = Donors::fetch_all();

						if ( ! empty( $donors ) && is_array( $donors ) ) {

							foreach( $donors as $donor ) {

								?>

								<tr>
									<td><?php $donor->display( 'donor_id' ) ?></td>
									<td><?php $donor->display( 'donor_name' ) ?></td>
									<td><?php $donor->display( 'donor_gender' ) ?></td>
									<td><?php $donor->display( 'donor_weight' ) ?></td>
									<td><?php $donor->display( 'donor_birthdate' ) ?></td>
									<td><?php $donor->display( 'donor_blood_type' ) ?></td>
									<td><?php $donor->display( 'donor_distr_id' ) ?></td>
									<td><?php $donor->display( 'donor_phone' ) ?></td>
									<td><?php $donor->display( 'donor_email' ) ?></td>
								</tr>

								<?php

							}

						}

					?>

				</tbody>


			</table>

		</div> <!-- #content --><?php

		$this->template_footer();

	}

}
