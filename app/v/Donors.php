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
	 * @return void
	 * @since 0.2
	 */
	public function __invoke() {

		$can_add     = current_user_can( 'add_donor' );
		$can_edit    = current_user_can( 'edit_donor' );
		$can_delete  = current_user_can( 'delete_donor' );
		$can_manage  = current_user_can( 'manage_donors' );
		$can_approve = current_user_can( 'approve_donor' );

		$this->template_header(); ?>

			<?php if ( $can_add ) : ?>
			<a href="<?php site_url( array( 'page' => 'add-donor' ) ) ?>" class="add-link">
				<button type="button">أضف متبرع جديد</button>
			</a>
			<?php endif; ?>

			<table id="table-donors" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
					<th>الجنس</th>
					<th>العمر</th>
					<th>فصيلة الدم</th>
					<th>المدينة/المديرية</th>
					<th>رقم التلفون</th>
					<?php if ( $can_manage ) : ?>
					<th>الإجراءات</th>
					<?php endif; ?>
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
									<td><?php echo $donor->get_gender_label() ?></td>
									<td><?php echo $donor->get_age() ?></td>
									<td><?php $donor->display( 'donor_blood_group' ) ?></td>
									<td><?php printf( '%s/%s', $donor->get_city_name(), $donor->get_district_name() ) ?></td>
									<td><?php $donor->display( 'donor_phone' ) ?></td>
									<?php if ( $can_manage ) : ?>
									<td>
										<?php if ( $can_edit ) : ?>
										<a href="<?php site_url( array( 'page' => 'edit-donor', 'id' => $donor->get( 'donor_id' ) ) ) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
										<?php endif; ?>
										<?php if ( $can_delete ) : ?>
										<a href="<?php site_url( array( 'page' => 'donors', 'action' => 'delete_donor', 'id' => $donor->get( 'donor_id' ) ) ) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
										<?php endif; ?>
										<?php if ( $can_approve && $donor->is_pending() ) : ?>
										<a href="<?php site_url( array( 'page' => 'donors', 'action' => 'approve_donor', 'id' => $donor->get( 'donor_id' ) ) ) ?>" class="approve-link"><i class="fa fa-check"></i></a>
										<?php endif; ?>
									</td>
									<?php endif; ?>
								</tr>

								<?php

							}

						}

					?>

				</tbody>

			</table><?php

		$this->template_footer();

	}

	/**
	 * @return string
	 * @since 0.2
	 */
	public function get_title() {
		return 'المتبرعين';
	}

	/**
	 * @return string
	 * @since 0.5.6
	 */
	public function get_fa_icon() {
		return '<i class="fa fa-table"></i>';
	}

}
