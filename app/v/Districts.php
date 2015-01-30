<?php

namespace eBloodBank;

/**
 * @since 0.3
 */
class Districts_View extends Default_View {

	/**
	 * @return string
	 * @since 0.3
	 */
	public function get_title() {
		return 'المديريات';
	}

	/**
	 * @return void
	 * @since 0.3
	 */
	public function __invoke() {

		$can_add    = current_user_can( 'add_distr' );
		$can_edit   = current_user_can( 'edit_distr' );
		$can_delete = current_user_can( 'delete_distr' );
		$can_manage = current_user_can( 'manage_distrs' );

		$this->template_header(); ?>

			<?php if ( $can_add ) : ?>
			<a href="<?php site_url( array( 'page' => 'add-distr' ) ) ?>" class="add-link">
				<button type="button">أضف مديرية جديدة</button>
			</a>
			<?php endif; ?>

			<table id="table-distrs" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
					<th>المدينة</th>
					<?php if ( $can_manage ) : ?>
					<th>الإجراءات</th>
					<?php endif; ?>
				</thead>

				<tbody>

					<?php

						$distrs = Districts::fetch_all();

						if ( ! empty( $distrs ) && is_array( $distrs ) ) {

							foreach( $distrs as $distr ) {

								?>

								<tr>
									<td><?php $distr->display( 'distr_id' ) ?></td>
									<td><?php $distr->display( 'distr_name' ) ?></td>
									<td>
										<?php

											$city = Cites::fetch_by_ID( $distr->get( 'distr_city_id' ) );
											$city->display( 'city_name' );

										?>
									</td>
									<?php if ( $can_manage ) : ?>
									<td>
										<?php if ( $can_edit ) : ?>
										<a href="<?php site_url( array( 'page' => 'edit-distr', 'id' => $distr->get( 'distr_id' ) ) ) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
										<?php endif; ?>
										<?php if ( $can_delete ) : ?>
										<a href="<?php site_url( array( 'page' => 'distrs', 'action' => 'delete_distr', 'id' => $distr->get( 'distr_id' ) ) ) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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

}
