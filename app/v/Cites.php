<?php

namespace eBloodBank;

/**
 * @since 0.3
 */
class Cites_View extends Default_View {

	/**
	 * @return string
	 * @since 0.3
	 */
	public function get_title() {
		return 'المدن';
	}

	/**
	 * @return void
	 * @since 0.3
	 */
	public function __invoke() {

		$can_add    = current_user_can( 'add_city' );
		$can_edit   = current_user_can( 'edit_city' );
		$can_delete = current_user_can( 'del_city' );
		$can_manage = current_user_can( 'manage_cites' );

		$this->template_header(); ?>

			<?php if ( $can_add ) : ?>
			<a href="<?php site_url( array( 'page' => 'add-city' ) ) ?>" class="add-link">
				<button type="button">أضف مدينة جديدة</button>
			</a>
			<?php endif; ?>

			<table id="table-cites" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
					<?php if ( $can_manage ) : ?>
					<th>الإجراءات</th>
					<?php endif; ?>
				</thead>

				<tbody>

					<?php

						$cites = Cites::fetch_all();

						if ( ! empty( $cites ) && is_array( $cites ) ) {

							foreach( $cites as $city ) {

								?>

								<tr>
									<td><?php $city->display( 'city_id' ) ?></td>
									<td><?php $city->display( 'city_name' ) ?></td>
									<?php if ( $can_manage ) : ?>
									<td>
										<?php if ( $can_edit ) : ?>
										<a href="<?php site_url( array( 'page' => 'edit-city', 'id' => $city->get( 'city_id' ) ) ) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
										<?php endif; ?>
										<?php if ( $can_delete ) : ?>
										<a href="<?php site_url( array( 'page' => 'cites', 'action' => 'del_city', 'id' => $city->get( 'city_id' ) ) ) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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
