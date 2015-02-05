<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class User_View extends Default_View {

	/**
	 * @return void
	 * @since 0.2
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<form id="form-user" class="form-block" method="POST">

				<div>
					<label for="user_logon">اسم الدخول</label>
					<input type="text" name="user_logon" id="user_logon" required="required" />
				</div>

				<div>
					<label for="user_pass">كلمة المرور</label>
					<input type="password" name="user_pass" id="user_pass" required="required" />
				</div>

				<div>
					<label for="user_role">الرتبة</label>
					<select name="user_role" id="user_role">
						<?php foreach( Roles::get_roles() as $role ) : ?>
							<option value="<?php echo esc_attr( $role->slug ) ?>"><?php echo $role->title ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div>
					<button type="submit">ارسال</button>
				</div>

				<input type="hidden" name="action" value="submit_user" />

			</form><?php

		$this->template_footer();

	}

	/**
	 * @return string
	 * @since 0.2
	 */
	public function get_title() {

		switch ( CURRENT_PAGE ) {

			case 'user':
			case 'view-user':
				return 'عرض مستخدم';

			case 'add-user':
				return 'إضافة مستخدم';

			case 'edit-user':
				return 'تحرير مستخدم';

		}

	}

	/**
	 * @return string
	 * @since 0.5.6
	 */
	public function get_fa_icon() {

		switch ( CURRENT_PAGE ) {

			case 'add-user':
				return '<i class="fa fa-plus"></i>';

		}

	}

}
