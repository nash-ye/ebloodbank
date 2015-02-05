<?php

namespace eBloodBank;

/**
 * @since 0.5.6
 */
class Dashboard_View extends Default_View {

	/**
	 * @return void
	 * @since 0.5.6
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<form id="signin-form" method="POST" class="form-block">

				<div>
					<label for="user_logon">اسم المستخدم</label>
					<input type="text" name="user_logon" id="user_logon" required />
				</div>

				<div>
					<label for="user_pass">كلمة المرور</label>
					<input type="password" name="user_pass" id="user_pass" required />
				</div>

				<div>
					<button type="submit">دخول</button>
				</div>

				<input type="hidden" name="action" value="signin" />

			</form><?php

		$this->template_footer();

	}

	/**
	 * @return string
	 * @since 0.5.6
	 */
	public function get_title() {
		return 'تسجيل الدخول';
	}

}
