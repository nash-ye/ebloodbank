<?php

namespace eBloodBank;

/**
 * @since 0.5.4
 */
class Banks_View extends Default_View {

	/**
	 * @return void
	 * @since 0.5.4
	 */
	public function __invoke() {

		$can_add    = current_user_can( 'add_bank' );
		$can_edit   = current_user_can( 'edit_bank' );
		$can_delete = current_user_can( 'delete_bank' );
		$can_manage = current_user_can( 'manage_banks' );

		$this->template_header(); ?>

			<?php if ( $can_add ) : ?>
			<a href="<?php site_url( array( 'page' => 'add-bank' ) ) ?>" class="add-link">
				<button type="button">أضف بنك دم جديد</button>
			</a>
			<?php endif; ?>

			<table id="table-banks" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
					<th>التلفون</th>
					<th>البريد الإلكتروني</th>
					<th>المدينة/المديرية</th>
					<?php if ( $can_manage ) : ?>
					<th>الإجراءات</th>
					<?php endif; ?>
				</thead>

				<tbody>

					<?php

						$banks = Banks::fetch_all();

						if ( ! empty( $banks ) && is_array( $banks ) ) {

							foreach( $banks as $bank ) {

								?>

								<tr>
									<td><?php $bank->display( 'bank_id' ) ?></td>
									<td>
										<a href="<?php site_url( array( 'page' => 'stocks', 'bank_id' => $bank->get_ID() ) ) ?>">
											<?php $bank->display( 'bank_name' ) ?>
										</a>
									</td>
									<td><?php $bank->display( 'bank_phone' ) ?></td>
									<td><?php $bank->display( 'bank_email' ) ?></td>
									<td><?php printf( '%s/%s', $bank->get_city_name(), $bank->get_district_name() ) ?></td>
									<?php if ( $can_manage ) : ?>
									<td>
										<?php if ( $can_edit ) : ?>
										<a href="<?php site_url( array( 'page' => 'edit-bank', 'id' => $bank->get_ID() ) ) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
										<?php endif; ?>
										<?php if ( $can_delete ) : ?>
										<a href="<?php site_url( array( 'page' => 'banks', 'action' => 'delete_bank', 'id' => $bank->get_ID() ) ) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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
	 * @since 0.5.4
	 */
	public function get_title() {
		return 'بنوك الدم';
	}

	/**
	 * @return string
	 * @since 0.5.6
	 */
	public function get_fa_icon() {
		return '<i class="fa fa-table"></i>';
	}

}
