<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Test_Types_View extends Default_View {

	/**
	 * @return string
	 * @since 0.5.3
	 */
	public function get_title() {
		return 'أنواع الفحوصات';
	}

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function __invoke() {

		$can_add    = current_user_can( 'add_test_type' );
		$can_edit   = current_user_can( 'edit_test_type' );
		$can_delete = current_user_can( 'delete_test_type' );
		$can_manage = current_user_can( 'manage_test_types' );

		$this->template_header(); ?>

			<?php if ( $can_add ) : ?>
			<a href="<?php site_url( array( 'page' => 'add-test-type' ) ) ?>" class="add-link">
				<button type="button">أضف نوع فحص جديد</button>
			</a>
			<?php endif; ?>

			<table id="table-test-types" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
					<?php if ( $can_manage ) : ?>
					<th>الإجراءات</th>
					<?php endif; ?>
				</thead>

				<tbody>

					<?php

						$test_types = Test_Types::fetch_all();

						if ( ! empty( $test_types ) && is_array( $test_types ) ) {

							foreach( $test_types as $test_type ) {

								?>

								<tr>
									<td><?php $test_type->display( 'tt_id' ) ?></td>
									<td><?php $test_type->display( 'tt_title' ) ?></td>
									<?php if ( $can_manage ) : ?>
									<td>
										<?php if ( $can_edit ) : ?>
										<a href="<?php site_url( array( 'page' => 'edit-test-type', 'id' => $test_type->get( 'tt_id' ) ) ) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
										<?php endif; ?>
										<?php if ( $can_delete ) : ?>
										<a href="<?php site_url( array( 'page' => 'test-types', 'action' => 'delete_test_type', 'id' => $test_type->get( 'tt_id' ) ) ) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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
