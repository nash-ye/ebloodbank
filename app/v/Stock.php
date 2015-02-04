<?php

namespace eBloodBank;

/**
 * @since 0.5.3
 */
class Stock_View extends Default_View {

	/**
	 * @return string
	 * @since 0.5.3
	 */
	public function get_title() {

		switch ( CURRENT_PAGE ) {

			case 'add-stock':
				return 'إضافة مخزون';

			case 'edit-stock':
				return 'تحرير مخزون';

		}

	}

	/**
	 * @return void
	 * @since 0.5.3
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<form id="form-stock" class="form-block" method="POST">

				<?php if ( empty( $_GET['bank_id'] ) ) : ?>

				<div>
					<label for="stock_bank_id">بنك الدم</label>
					<select name="stock_bank_id" id="stock_bank_id" required="required">
						<?php foreach( Banks::fetch_all() as $bank ) : ?>
							<option value="<?php $bank->display( 'bank_id', 'attr' ) ?>"><?php $bank->display( 'bank_name' ) ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<?php else : ?>

				<input type="hidden" name="stock_bank_id" value="<?php echo (int) $_GET['bank_id'] ?>" />

				<?php endif; ?>

				<div>
					<label for="stock_blood_group">فصيلة الدم</label>
					<select name="stock_blood_group" id="stock_blood_group" required="required">
						<?php foreach( Stock::$blood_groups as $blood_group ) : ?>
							<option><?php echo $blood_group ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div>
					<label for="stock_quantity">الكمية</label>
					<input type="number" name="stock_quantity" id="stock_quantity" required="required" />
				</div>

				<input type="hidden" name="action" value="submit_stock" />

				<div>
					<button type="submit">ارسال</button>
				</div>

			</form><?php

		$this->template_footer();

	}

}
