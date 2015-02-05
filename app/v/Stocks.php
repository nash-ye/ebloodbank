<?php

namespace eBloodBank;

/**
 * @since 0.5.4
 */
class Stocks_View extends Default_View {

	/**
	 * @var array
	 * @since 0.5.4
	 */
	public $filter_args = arraY();

	/**
	 * @return void
	 * @since 0.5.4
	 */
	public function __invoke() {

		$bank_id = (int) $this->filter_args['bank_id'];

		$can_add    = current_user_can( 'add_stock' );
		$can_edit   = current_user_can( 'edit_stock' );
		$can_delete = current_user_can( 'delete_stock' );
		$can_manage = current_user_can( 'manage_stocks' );

		$this->template_header(); ?>

			<?php if ( $can_add ) : ?>
			<a href="<?php site_url( array( 'page' => 'add-stock', 'bank_id' => $bank_id ) ) ?>" class="btn add-link">أضف مخزون دم جديد</a>
			<?php endif; ?>

			<table id="table-test-types" class="list-table">

				<thead>
					<tr>
						<th>#</th>
						<th>فصيلة الدم</th>
						<th>الكمية المتوفرة</th>
						<?php if ( $can_manage ) : ?>
						<th>الإجراءات</th>
						<?php endif; ?>
					</tr>
				</thead>

				<tbody>

					<?php

						$stocks = Stocks::fetch_multi( 'SELECT * FROM stock WHERE stock_bank_id = ?', array( $bank_id ) );

						if ( ! empty( $stocks ) && is_array( $stocks ) ) {

							foreach( $stocks as $stock ) {

								?>

								<tr>
									<td><?php $stock->display( 'stock_id' ) ?></td>
									<td><?php $stock->display( 'stock_blood_group' ) ?></td>
									<td><?php $stock->display( 'stock_quantity' ) ?></td>
									<?php if ( $can_manage ) : ?>
									<td>
										<?php if ( $can_edit ) : ?>
										<a href="<?php site_url( array( 'page' => 'edit-stock', 'id' => $stock->get_ID() ) ) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
										<?php endif; ?>
										<?php if ( $can_delete ) : ?>
										<a href="<?php site_url( array( 'page' => 'stocks', 'action' => 'delete_stock', 'id' => $stock->get_ID() ) ) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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
		return 'مخزونات بنك الدم';
	}

	/**
	 * @return string
	 * @since 0.5.6
	 */
	public function get_fa_icon() {
		return '<i class="fa fa-table"></i>';
	}

}
