<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Tests_View extends Default_View {

	/**
	 * @return string
	 * @since 0.5.3
	 */
	public function get_title() {
		return 'الفحوصات الطبية';
	}

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function __invoke() {

		$can_add    = current_user_can( 'add_test' );
		$can_edit   = current_user_can( 'edit_test' );
		$can_delete = current_user_can( 'delete_test' );
		$can_manage = current_user_can( 'manage_tests' );

		$this->template_header(); ?>

			<?php if ( $can_add ) : ?>
			<a href="<?php site_url( array( 'page' => 'add-test' ) ) ?>" class="add-link">
				<button type="button">أضف فحص جديد</button>
			</a>
			<?php endif; ?>

			<table id="table-tests" class="list-table">

				<thead>
					<th>#</th>
					<th>النوع</th>
					<th>المتبرع</th>
					<th>التاريخ</th>
					<?php if ( $can_manage ) : ?>
					<th>الإجراءات</th>
					<?php endif; ?>
				</thead>

				<tbody>

					<?php

						$tests = Tests::fetch_all();

						if ( ! empty( $tests ) && is_array( $tests ) ) {

							foreach( $tests as $test ) {

								?>

								<tr>
									<td><?php $test->display( 'test_id' ) ?></td>
									<td>
										<?php
											$test_type_id = (int) $test->get( 'test_type_id' );
											$test_type = Test_Types::fetch_by_ID( $test_type_id );
											$test_type->display( 'tt_title' );
										?>
									</td>
									<td>
										<?php
											$test_donor_id = (int) $test->get( 'test_donor_id' );
											$donor = Donors::fetch_by_ID( $test_donor_id );
											$donor->display( 'donor_name' );
										?>
									</td>
									<td><?php $test->display( 'test_date' ) ?></td>
									<?php if ( $can_manage ) : ?>
									<td>
										<?php if ( $can_edit ) : ?>
										<a href="<?php site_url( array( 'page' => 'edit-test', 'id' => $test->get_ID() ) ) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
										<?php endif; ?>
										<?php if ( $can_delete ) : ?>
										<a href="<?php site_url( array( 'page' => 'tests', 'action' => 'delete_test', 'id' => $test->get_ID() ) ) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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
